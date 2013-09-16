<?php
/**
 * Standard Library
 *
 * @package Ebd_Ecommerce
 * @subpackage Ebd_Ecommerce_OrderTotal
 */

namespace Ebd\Ecommerce\OrderTotal;

interface SubtotalInterface
{
    public function getCode();
    public function process();
}
