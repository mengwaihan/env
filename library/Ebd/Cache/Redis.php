<?php
/**
 * Standard Library
 *
 * @package Ebd_Cache
 */

namespace Ebd\Cache;

use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;

class Redis implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator|\Hints\ServiceLocator
     */
    protected $locator = null;

    /**
     * Cache and get stored value (APC cache)
     *
     * @param string $key
     * @param callable $generator
     * @param int $ttl
     * @return mixed
     */
    public static function get($key, $generator, $ttl = 0)
    {
        if ($this->locator->get('Redis')) {
            $redis = $this->locator->get('Redis');
            if ($redis->exists($key)) {
                return $redis->get($key);
            }

            $value = $generator();
            $redis->set($key, $value, $ttl);
            return $value;
        }

        return $generator();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $serviceLocator
     * @return AbstractPluginManager
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