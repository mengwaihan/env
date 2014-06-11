<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_ShoppingCart
 */

namespace Ebd\Ecommerce\ShoppingCart;

use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;

abstract class AbstractCartItemCollection implements ServiceLocatorAwareInterface, CartItemCollectionInterface, \IteratorAggregate, \Countable
{
    /**
     * @var AbstractCartItem[]
     */
    protected $items = array();

    /**
     * @var StorerInterface
     */
    protected $storer = null;

    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * Set a storer object and load all items from database
     *
     * @param StorerInterface $storer
     */
    public function load(StorerInterface $storer)
    {
        if ($storer instanceof ServiceLocatorAwareInterface && $this->locator) {
            $storer->setServiceLocator($this->locator);
        }
        $this->storer = $storer;

        $items = $this->storer->getItems();
        foreach ($items as $item) {
            $item->setCollection($this);
            $this->items[$item->getIdentifier()] = $item;
        }
    }

    /**
     * Save and restore
     *
     * @return AbstractCartItemCollection
     */
    public function restore()
    {
        // Copy guest basket to customer basket, then clear guest basket
        $this->storer->restore();

        // replace session items with the db items
        $this->items = $this->storer->getItems();

        // set collection to cart items
        foreach ($this->items as $item) {
            $item->setCollection($this);
        }

        // return
        return $this;
    }

    /**
     * Add cart item
     *
     * @param AbstractCartItem $item
     * @return AbstractCartItemCollection
     */
    public function addItem(AbstractCartItem $item)
    {
        // set service locator
        if ($item instanceof ServiceLocatorAwareInterface && $this->locator) {
            $item->setServiceLocator($this->locator);
        }

        // save cart item collection to item
        $item->setCollection($this);

        /* @var $identifier string */
        $identifier = $item->getIdentifier();

        // already exists (upadte)
        if (isset($this->items[$identifier])) {
            /* @var $oldItem CartItem */
            $oldItem = $this->items[$identifier];
            $oldItem->setQuantity($oldItem->getQuantity() + $item->getQuantity());
        }
        // doesn't exist (insert)
        else {
            $this->storer->replace($item);
            $this->items[$identifier] = $item;
        }

        // return
        return $this;
    }

    /**
     * Remove cart item from items by item identifier
     *
     * @param string $identifier
     * @return AbstractCartItem
     */
    public function removeItem($identifier)
    {
        if (isset($this->items[$identifier])) {
            $item = $this->items[$identifier];
            $this->storer->delete($identifier);
            unset($this->items[$identifier]);
            return $item;
        }
    }

    /**
     * Remove cart items by product id
     *
     * @param string $id
     * @return AbstractCartItemCollection
     */
    public function removeItemsByProductId($id)
    {
        $id = (string) $id;
        foreach ($this->items as $identifier => $item) {
            if ($item->getProductId() === $id) {
                $this->storer->delete($identifier);
                unset($this->items[$identifier]);
            }
        }
        return $this;
    }

    /**
     * Clear all the cart items
     *
     * @return AbstractCartItemCollection
     */
    public function destroy()
    {
        $this->storer->destroy();
        $this->items = array();
        return $this;
    }

    /**
     * Get cart item by item identifier
     *
     * @param string $identifier
     * @return AbstractCartItem|null
     */
    public function getItem($identifier)
    {
        return isset($this->items[$identifier]) ? $this->items[$identifier] : null;
    }

    /**
     * Get cart items by product id (or get all cart items if $productId is null)
     *
     * @param string|null $productId
     * @return AbstractCartItem[]
     */
    public function getItems($productId = null)
    {
        if (null === $productId) {
            return $this->items;
        }

        $productId = (string) $productId;
        $items = array();
        foreach ($this->items as $identifier => $item) {
            if ($item->getProductId() === $productId) {
                $items[$identifier] = $item;
            }
        }
        return $items;
    }

    /**
     * Get storer object
     *
     * @return StorerInterface
     */
    public function getStorer()
    {
        return $this->storer;
    }

    /**
     * Get the total quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        $quantity = 0;
        foreach ($this->items as $item) {
            $quantity += $item->getQuantity();
        }
        return $quantity;
    }

    /**
     * Calculate the total price
     *
     * @return float
     */
    public function total()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotalPrice();
        }
        return $total;
    }

    /**
     * Set a service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return AbstractEventDispatcher
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }

    /**
     * Implement \IteratorAggregate interface
     *
     * @return \IteratorIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getItems());
    }

    /**
     * Implement \Countable interface
     * @return int
     */
    public function count()
    {
        return count($this->getItems());
    }

    /**
     * Get unique identifier of shopping cart
     *
     * @return string
     */
    public function getIdentifier()
    {
        $hash = '';
        foreach ($this->items  as $item) {
            $hash .= $item->getIdentifier() . '-' . $item->getQuantity() . ';';
        }
        return substr(md5($hash), 0, 8);
    }
}