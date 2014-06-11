<?php
/**
 * Standard Library
 *
 * @package Ebd_ServiceLocator
 */

namespace Ebd\ServiceLocator;

interface ServiceLocatorAwareInterface
{
    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator);

    /**
     * Get service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator();
}

