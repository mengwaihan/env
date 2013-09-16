<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_ShoppingCart
 */

namespace Ebd\Ecommerce\ShoppingCart;

interface StorerInterface
{
    /**
     * Insert some cart item
     *
     * @param AbstractCartItem $item
     * @return boolean
     */
    public function insert(AbstractCartItem $item);

    /**
     * Update some cart item
     *
     * @param AbstractCartItem $item
     * @return boolean
     */
    public function update(AbstractCartItem $item);

    /**
     * It will update if exsits, else it will insert
     *
     * @param AbstractCartItem $item
     * @return boolean
     */
    public function replace(AbstractCartItem $item);

    /**
     * Copy guest basket to customer basket, then clear guest basket.
     *
     * @return boolean
     */
    public function restore();

    /**
     * Delete a cart item
     *
     * @param string $identifier
     * @return boolean
     */
    public function delete($identifier);

    /**
     * Delete all the cart items
     *
     * @return boolean
     */
    public function destroy();

    /**
     * Whether or not exist some item
     *
     * @param string $identifier
     * @return boolean
     */
    public function has($identifier);

    /**
     * Get quantity
     *
     * @param string $identifier
     * @return int
     */
    public function getQuantity($identifier);

    /**
     * Get all the cart items
     *
     * @return AbstractCartItem[]
     */
    public function getItems();
}