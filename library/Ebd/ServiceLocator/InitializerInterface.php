<?php
/**
 * Standard Library
 *
 * @package Ebd_ServiceLocator
 */

namespace Ebd\ServiceLocator;

/**
 * It will automatically run initialize() before get a cached plugin via plugin manager.
 */
interface InitializerInterface
{
    public function initialize();
}