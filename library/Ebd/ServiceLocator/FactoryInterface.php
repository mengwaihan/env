<?php
/**
 * Standard Library
 *
 * @package Ebd_ServiceLocator
 */

namespace Ebd\ServiceLocator;

interface FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocator $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocator $serviceLocator);
}
