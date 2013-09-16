<?php
/**
 * Standard Library
 *
 * @package Ebd_Model
 */

namespace Ebd\Model;

use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;

/**
 * @property \Ebd\Db\Pdo $db The DB instance
 */
abstract class AbstractModel implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator;

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        if ('db' === $name) {
            return $this->locator->get('Db');
        }

        if ('models' === $name) {
            return $this->locator->get('Ebd\Model\ModelManager');
        }

        throw new \InvalidArgumentException("Invalid property: $name");
    }

    /**
     * Set a service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return AbstractEventDispatcher
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }
}