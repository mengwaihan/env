<?php
/**
 * Standard Library
 *
 * @package Ebd_Utils
 * @see Ebd\Cache\Apc
 * @see Ebd\Cache\Redis
 */

namespace Ebd\Utils;

class Cache
{
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
        if (APC_LOADED) {
            $value = apc_fetch($key, $success);
            if ($success) {
                return $value;
            }

            $value = $generator();
            apc_store($key, $value, $ttl);
            return $value;
        }

        return $generator();
    }
}