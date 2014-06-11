<?php

namespace Ebd\Ecommerce\Order;

interface OrderInterface
{
    /**
     * @return \Ebd\Ecommerce\ShoppingCart\CartItemInterface[]
     */
    public function getItems();

    /**
     * @return array
     */
    public function getUserAddress();

    /**
     * @return array
     */
    public function getShippingAddress();

    /**
     * @return array
     */
    public function getBillingAddress();

    /**
     * @return array
     */
    public function getMore();

    /**
     * @return array
     */
    public function getTotals();
}
