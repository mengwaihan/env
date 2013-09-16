<?php
/**
 * Standard Library
 *
 * @package Ebd_Controller
 * @subpackage Ebd_Controller_Plugin
 */

namespace Ebd\Controller\Plugin;

use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;

class Param implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @param string|null $name
     * @param mixed $default
     * @return mixed
     */
    public function __invoke($name = null, $default = null)
    {
        $params = $this->locator->get('Params');

        if (null === $name) {
            return $params;
        }
        return isset($params[$name]) ? $params[$name] : $default;
    }

    /**
     * Whether or not exist some parameter and value
     *
     * @param string $key
     * @param string|null $item
     * @return boolean
     */
    public function has($key, $item = null)
    {
        $params = $this->locator->get('Params');

        if (empty($params[$key])) {
            return false;
        }

        if (null == $item) {
            return true;
        }

        if (in_array($item, (array) $params[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return Controller
     */
    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->locator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->locator;
    }
}