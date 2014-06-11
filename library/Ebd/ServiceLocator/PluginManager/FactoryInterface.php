<?php
/**
 * Standard Library
 *
 * @package Ebd_ServiceLocator
 */

namespace Ebd\ServiceLocator\PluginManager;

interface FactoryInterface
{
    /**
     * @return object
     */
    public function factory();
}