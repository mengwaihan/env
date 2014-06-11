<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_ShoppingCart
 */

namespace Ebd\Ecommerce\ShoppingCart;

abstract class AbstractCartItem implements CartItemInterface
{
    /**
     * Identify the cart item
     *
     * @var string
     */
    protected $identifier = null;

    /**
     * product id
     *
     * @var string
     */
    protected $productId = null;

    /**
     * It only saves the basic information of options.
     * e.g. option_id, value_id, price
     *
     * @var array
     */
    protected $options = null;

    /**
     * @var int
     */
    protected $quantity = null;

    /**
     * It will save product details.
     * e.g. id, model, name, color, price, image, [stock]
     *
     * @var array
     */
    protected $product = null;

    /**
     * It will save the full option information.
     * e.g. option_id, option, value_id, value, [price]
     *
     * @var array
     */
    protected $fullOptions = null;

    /**
     * @var float
     */
    protected $productPrice = null;

    /**
     * @var float
     */
    protected $optionsPrice = null;

    /**
     * @var float
     */
    protected $itemPrice = null;

    /**
     * @var float
     */
    protected $totalPrice = null;

    /**
     * @var CartItemCollectionInterface
     */
    protected $collection = null;

    /**
     * Construcotr
     *
     * @param string $productId
     * @param int $quantity
     * @param array $options
     */
    public function __construct($productId, $quantity = 1, array $options = array())
    {
        $this->productId = (string) $productId;
        $this->quantity = $quantity;
        $this->options = $options;
    }

    /**
     * Get identifier of cart item
     *
     * @return string
     */
    public function getIdentifier()
    {
        if (null === $this->identifier) {
            if ($this->options) {
                $suffix = substr(md5(serialize($this->options)), 0, 8);
                $this->identifier = $this->productId . '-' . $suffix;
            } else {
                $this->identifier = $this->productId;
            }
        }
        return $this->identifier;
    }

    /**
     * Get product id
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Get options of cart item
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get quantity of cart item
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get product price
     *
     * @return float
     */
    public function getProductPrice()
    {
        if (null === $this->productPrice) {
            $product = $this->getProduct();
            $this->productPrice = empty($product['price']) ? 0 : $product['price'];
        }
        return $this->productPrice;
    }

    /**
     * Get options price
     *
     * @return float
     */
    public function getOptionsPrice()
    {
        if (null === $this->optionsPrice) {
            $price = 0;
            $fullOptions = $this->getFullOptions();
            foreach ($fullOptions as $option) {
                $price += empty($option['price']) ? 0 : $option['price'];
            }
            $this->optionsPrice = $price;
        }
        return $this->optionsPrice;
    }

    /**
     * Get item price
     *
     * @return float
     */
    public function getItemPrice()
    {
        if (null === $this->itemPrice) {
            $this->itemPrice = $this->getProductPrice() + $this->getOptionsPrice();
        }
        return $this->itemPrice;
    }

    /**
     * Get total price
     *
     * @return float
     */
    public function getTotalPrice()
    {
        if (null === $this->totalPrice) {
            $this->totalPrice = $this->getItemPrice() * $this->getQuantity();
        }
        return $this->totalPrice;
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     * @return boolean
     */
    public function setQuantity($quantity)
    {
        // no change
        if ($quantity == $this->quantity) {
            return true;
        }

        $this->quantity = $quantity;
        $this->reset();

        if ($this->collection) {
            // update
            if ($this->quantity > 0) {
                $storer = $this->collection->getStorer();
                $result = $storer->replace($this);
            }

            // remove from collection
            else {
                $result = $this->collection->removeItem($this->getIdentifier());
            }

            // return
            return false !== $result;
        }

        // always true
        return true;
    }

    /**
     * Remove current cart item from cart item collection
     *
     * @return boolean
     */
    public function remove()
    {
        if ($this->collection) {
            $result = $this->collection->removeItem($this->getIdentifier());
            return false !== $result;
        }
        return false;
    }

    /**
     * Set cart item collection object
     *
     * @param CartItemCollectionInterface $collection
     * @return AbstractCartItem
     */
    public function setCollection(CartItemCollectionInterface $collection)
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * Get cart item collection object
     *
     * @return CartItemCollectionInterface
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Reset the cart item (remove all the cached stuffs)
     */
    protected function reset()
    {
        $this->product = null;
        $this->fullOptions = null;
        $this->productPrice = null;
        $this->optionsPrice = null;
        $this->itemPrice = null;
        $this->totalPrice = null;
    }
}
