<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;

class AliasPageId implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @var array
     */
    protected $aliases = array();

    /**
     * Set/get the alias page id
     *
     * @param string|null $alias
     * @return AliasPageId|string|null
     */
    public function __invoke($alias = null)
    {
        $helpers = $this->locator->get('Ebd\View\HelperManager');
        $pageId = $helpers->pageId();

        // set alias
        if ($alias) {
            $this->aliases[$pageId] = $alias;
            return $this;
        }

        // get alias
        return isset($this->aliases[$pageId]) ? $this->aliases[$pageId] : null;
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

