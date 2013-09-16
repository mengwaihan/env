<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpacage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;
use Ebd\ServiceLocator\InitializerInterface;
use Ebd\Router\Router;
use Ebd\Utils\Http;
use Ebd\Utils\String;

class Url implements ServiceLocatorAwareInterface, InitializerInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @var Router
     */
    protected $router = null;

    /**
     * @var string
     */
    private $path = null;

    /**
     * @var array
     */
    private $params = null;

    /**
     * @var boolean
     */
    private $https = false;

    /**
     * @var boolean
     */
    private $forceHost = false;

    /**
     * Initialize the URL
     *
     * @param string $path
     * @param array $params
     * @param boolean $https
     * @param boolean $forceHost
     * @return Url
     */
    public function __invoke($path = 'default/index', $params = null, $https = false, $forceHost = false)
    {
        $this->path = $path;
        $this->params = (array) $params;
        $this->https = $https;
        $this->forceHost = $forceHost;
        return $this;
    }

    /**
     * Reset the object
     *
     * @return Url
     */
    public function initialize()
    {
        $this->path = null;
        $this->params = null;
        $this->https = false;
        $this->forceHost = false;
        return $this;
    }

    /**
     * Set action path
     *
     * @param string $path
     * @return Url
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get action path
     *
     * @return string
     */
    public function getPath()
    {
        if (null === $this->path) {
            // controller mode
            if ($this->locator->has('Controller')) {
                $params = $this->locator->get('Params');
                $this->setPath($params['_controller'] . '/' . $params['_action']);
            }
            // not controller mode
            else {
                $this->setPath(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            }
        }
        return $this->path;
    }

    /**
     * Set HTTPS
     *
     * @param boolean $is
     * @return Url
     */
    public function setHttps($is)
    {
        $this->https = $is;
        return $this;
    }

    /**
     * Include Host
     *
     * @param boolean $force
     * @return Url
     */
    public function forceHost($force)
    {
        $this->forceHost = $force;
        return $this;
    }

    /**
     * Add parameters (only merge to the origin parameters)
     *
     * @param array $params
     * @return Url
     */
    public function addParams(array $params)
    {
        $this->getParams();

        foreach ($params as $key => $value) {
            if (
                isset($this->params[$key])
                && is_array($this->params[$key])
                || is_array($value)
            ) {
                $this->params[$key] = array_unique(array_merge((array) $this->params[$key], (array) $value));
            } else {
                $this->params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Toggle an array value
     *
     * @param string $arrKey
     * @param string $value
     * @return Url
     */
    public function toggleParam($arrKey, $value)
    {
        $this->getParams();

        if (isset($this->params[$arrKey]) && in_array($value, (array) $this->params[$arrKey])) {
            return $this->removeParams(array($arrKey => $value));
        }
        return $this->addParams(array($arrKey => $value));
    }

    /**
     * Remove some parameters
     *
     * @param array|string $array
     * @return Url
     */
    public function removeParams($array)
    {
        $this->getParams();

        // diff
        foreach ((array) $array as $key => $item) {
            if (is_numeric($key)) {
                unset($this->params[$item]);
            } elseif (array_key_exists($key, $this->params)) {
                $this->params[$key] = array_diff((array) $this->params[$key], (array) $item);
            }
        }

        return $this;
    }

    /**
     * Keep some parameters
     *
     * @param array|string $array
     * @return Url
     */
    public function keepParams($array)
    {
        $this->getParams();

        // remove the unnecessary parameters
        $array = (array) $array;
        foreach ($this->params as $key => $item) {
            if (!in_array($key, $array)) {
                unset($this->params[$key]);
            }
        }

        return $this;
    }

    /**
     * Sort some key
     *
     * @param string $key
     * @param string $helper
     * @param mixed $more
     * @return Url
     */
    public function sort($key, $helper)
    {
        if (isset($this->params[$key])) {
            $args = array_slice(func_get_args(), 2);
            array_unshift($args, (array) $this->params[$key]);
            /* @var $helpers \Ebd\View\HelperManager */
            $helpers = $this->locator->get('Ebd\View\HelperManager');
            $this->params[$key] = call_user_func_array(array($helpers, $helper), $args);
        }
        return $this;
    }

    /**
     * Create URL
     *
     * @return string
     */
    public function __toString()
    {
        // Path
        $path = $this->getPath();

        // Params
        $params = $this->getParams();

        // Not controller mode
        $includeHost = strpos($path, '//') !== false;
        if ($includeHost || strpos($path, '.') !== false) {
            $query = http_build_query($params);
            $url = $path . String::concat($query, '?');

            if ($includeHost) {
                return $url;
            }
            if ($path{0} != '/') {
                $url = BASE_PATH . $url;
            }
            if (Http::isHttps() === $this->https && false === $this->forceHost) {
                return $url;
            }

            if ($this->https) {
                $host = defined('HTTPS_SERVER') ? HTTPS_SERVER : 'https://' . $_SERVER['HTTP_HOST'];
            } else {
                $host = defined('HTTP_SERVER') ? HTTP_SERVER : 'http://' . $_SERVER['HTTP_HOST'];
            }
            return $host . $url;
        }

        // crerate url
        $url = $this->router->createUrl($path, $params);
		if (Http::isHttps() === $this->https && false === $this->forceHost) {
			return $url;
		}
        if ($this->https) {
            $host = defined('HTTPS_SERVER') ? HTTPS_SERVER : 'https://' . $_SERVER['HTTP_HOST'];
        } else {
            $host = defined('HTTP_SERVER') ? HTTP_SERVER : 'http://' . $_SERVER['HTTP_HOST'];
        }
        return $host . $url;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $locator
     * @return Url
     */
    public function setServiceLocator(ServiceLocator $locator)
    {
        $this->locator = $locator;
        $this->router = $this->locator->get('Router');
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

    /**
     * Get all the parameters
     *
     * @return array
     */
    protected function getParams()
    {
        if (null === $this->params) {
            $this->params = $this->locator->get('Params');
            unset($this->params['_controller'], $this->params['_action']);
        }
        return $this->params;
    }
}