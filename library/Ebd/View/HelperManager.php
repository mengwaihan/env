<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 */

namespace Ebd\View;

use Ebd\ServiceLocator\PluginManager\AbstractPluginManager;
use Ebd\Loader\Autoloader;

/**
 * @property \Ebd\View\Helper\Action $action
 * @property \Ebd\View\Helper\Controller $controller
 * @property \Ebd\View\Helper\PageId $pageId
 * @property \Ebd\View\Helper\Cycle $cycle
 * @property \Ebd\View\Helper\Date $date
 * @property \Ebd\View\Helper\Escape $escape
 * @property \Ebd\View\Helper\Url $url
 * @property \Ebd\View\Helper\SelfUrl $selfUrl
 * @property \Ebd\View\Helper\Urlencode $urlencode
 * @property \Ebd\View\Helper\Urldecode $urldecode
 *
 * @method \Ebd\View\Helper\Action action() Action Name
 * @method \Ebd\View\Helper\Controller controller() Controller Name
 * @method \Ebd\View\Helper\PageId pageId() Controller + Action
 * @method \Ebd\View\Helper\Cycle cycle(array $data = array(), $name = self::DEFAULT_NAME) Helper for alternating between set of values
 * @method \Ebd\View\Helper\Date date($date, $format = 'Y-m-d H:i:s', $default = null) Format date
 * @method \Ebd\View\Helper\Escape escape($str) Escape the html string
 * @method \Ebd\View\Helper\Url url($path = 'default/index', $params = null, $https = false, $forceHost = false)
 * @method \Ebd\View\Helper\SelfUrl selfUrl($query = null, $escape = true) Get current url
 * @method \Ebd\View\Helper\Urlencode urlencode($str, $raw = true) urlencode
 * @method \Ebd\View\Helper\Urldecode urldecode($str, $raw = true) urldecode
 * @method \Ebd\Controller\Plugin\Param param(string $name, $default) Get the parameter value
 */
class HelperManager extends AbstractPluginManager
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
        $class = Autoloader::find("View\\Helper\\$name");
        return $class ?: false;
    }
}
