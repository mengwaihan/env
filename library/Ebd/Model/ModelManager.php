<?php
/**
 * Standard Library
 *
 * @package Ebd_Model
 */

namespace Ebd\Model;

use Ebd\ServiceLocator\PluginManager\AbstractPluginManager;
use Ebd\Loader\Autoloader;

class ModelManager extends AbstractPluginManager
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
        $class = Autoloader::find("Model\\{$name}Model");
        return $class ?: false;
    }
}
