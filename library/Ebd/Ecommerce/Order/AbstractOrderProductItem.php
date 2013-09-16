<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_ShoppingCart
 */

namespace Ebd\Ecommerce\Order;

use Ebd\Ecommerce\ShoppingCart\CartItemInterface;

abstract class AbstractOrderProductItem implements CartItemInterface
{
    /**
     * Identify the cart item
     *
     * @var string
     */
    protected $identifier = null;

    /**
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
     * e.g. id, model, name, color, price, [image], [stock]
     *
     * @var array
     */
    protected $product = null;

    /**
     * It will save the full option information.
     * e.g. [option_id], option, [value_id], value, [price]
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
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Get the product id
     *
     * @return string
     */
    public function getProductId()
    {
        if (null !== $this->productId) {
            $product = $this->getProduct();
            if (isset($product['id'])) {
                $this->productId = $product['id'];
            }
        }
        return $this->productId;
    }

    /**
     * Get product details
     *
     * @param $field
     * @return array
     */
    public function getProduct($field = null)
    {
        return null === $field ? $this->product : $this->product[$field];
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        if (null === $this->options) {
            foreach ($this->getFullOptions() as $option) {
                if (isset($option['option_id']) && isset($option['value_id'])) {
                    $optionId = intval($option['option_id']);
                    $valueId = intval($option['value_id']);
                    if (0 === $valueId) {
                        $valueId = strval($option['value']);
                    }
                    $this->options[$optionId] = $valueId;
                }
            }
        }
        return $this->options;
    }

    /**
     * Get full options
     *
     * @return array
     */
    public function getFullOptions()
    {
        return $this->fullOptions;
    }

    /**
     * Get quantity
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
        if (null == $this->productPrice) {
            $product = $this->getProduct();
            if (isset($product['price'])) {
                $this->productPrice = $product['price'];
            } elseif (null !== $this->optionsPrice && null !== $this->itemPrice) {
                $this->productPrice = $this->itemPrice - $this->optionsPrice;
            }
        }
        return $this->productPrice;
    }

    /**
     * Get price of options
     *
     * @return float
     */
    public function getOptionsPrice()
    {
        if (null === $this->optionsPrice) {
            if (null !== $this->productPrice && null !== $this->itemPrice) {
                $this->optionsPrice = $this->itemPrice - $this->productPrice;
            } else {
                $price = 0;
                $fullOptions = $this->getFullOptions();
                foreach ($fullOptions as $option) {
                    $price += $option['price'];
                }
                $this->optionsPrice = $price;
            }
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
}
