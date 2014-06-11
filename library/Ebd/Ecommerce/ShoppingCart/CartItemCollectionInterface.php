<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_ShoppingCart
 */

namespace Ebd\Ecommerce\ShoppingCart;

interface CartItemCollectionInterface
{
    /**
     * Set a storer object and load all items from database
     *
     * @param StorerInterface $storer
     */
    public function load(StorerInterface $storer);

    /**
     * Save and restore
     *
     * @return CartItemCollectionInterface
     */
    public function restore();

    /**
     * Add cart item
     *
     * @param AbstractCartItem $item
     * @return CartItemCollectionInterface
     */
    public function addItem(AbstractCartItem $item);

    /**
     * Remove cart item from items by item identifier
     *
     * @param string $identifier
     * @return CartItemInterface
     */
    public function removeItem($identifier);

    /**
     * Remove cart items by product id
     *
     * @param string $id
     * @return CartItemCollectionInterface
     */
    public function removeItemsByProductId($id);

    /**
     * Clear all the cart items
     *
     * @return CartItemCollectionInterface
     */
    public function destroy();

    /**
     * Get cart item by item identifier
     *
     * @param string $identifier
     * @return CartItemInterface|null
     */
    public function getItem($identifier);

    /**
     * Get cart items by item id (or get all cart items if $id is null)
     *
     * @param string|null $productId
     * @return CartItemInterface[]
     */
    public function getItems($productId = null);

    /**
     * Get storer object
     *
     * @return StorerInterface
     */
    public function getStorer();

    /**
     * Get the total quantity
     *
     * @return int
     */
    public function getQuantity();

    /**
     * Calculate the total price
     *
     * @return float
     */
    public function total();
}