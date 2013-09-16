<?php
/**
 * Standard Library
 *
 * @package Ebd_Controller
 */

namespace Ebd\Controller;

use Ebd\ServiceLocator\PluginManager\AbstractPluginManager;
use Ebd\Loader\Autoloader;

class PluginManager extends AbstractPluginManager
{
    /**
     * Get the plugin class name
     *
     * @param string $name
     * @return string|boolean
     */
    public function getPluginClass($name)
    {
        $name = ucfirst($name);
        $class = Autoloader::find("Controller\\Plugin\\$name");
        return $class ?: false;
    }
}
