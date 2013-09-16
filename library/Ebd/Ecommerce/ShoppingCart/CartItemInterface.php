<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_ShoppingCart
 */

namespace Ebd\Ecommerce\ShoppingCart;

interface CartItemInterface
{
    /**
     * Get identifier of cart item (Unqiue ID)
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get product id of cart item
     *
     * @return string
     */
    public function getProductId();

    /**
     * Get product details
     *
     * @param $field
     * @return array
     */
    public function getProduct($field = null);

    /**
     * Get options of cart item
     *
     * @return array
     */
    public function getOptions();

    /**
     * Get full options for view
     * It should be impelemented by customized sub class
     *
     * The return value should include "option_id, value_id, option, value, price" fields
     *
     * @return array
     */
    public function getFullOptions();

    /**
     * Get quantity of cart item
     *
     * @return int
     */
    public function getQuantity();

    /**
     * Get the price of product
     * It should be impelemented by customized sub class
     *
     * @return float
     */
    public function getProductPrice();

    /**
     * Get the price of item options
     * It should be impelemented by customized sub class
     *
     * @return float
     */
    public function getOptionsPrice();

    /**
     * Get the item price of cart item (including product and options)
     * It should be impelemented by customized sub class
     *
     * Usually it is equal to productPrice + optionsPrice, but not always
     *
     * @return float
     */
    public function getItemPrice();

    /**
     * Get the total price
     * It should be impelemented by customized sub class
     *
     * Usually it is equal to itemPrice * quantity, but not always
     *
     * @return float
     */
    public function getTotalPrice();
}