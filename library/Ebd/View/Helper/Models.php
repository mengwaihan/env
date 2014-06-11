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

class Models implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    public function __get($name)
    {
        $models = $this->locator->get('Ebd\Model\ModelManager');
        return $models->{$name};
    }

    /**
     * Set service locator
     *
     * @param ServiceLocator $locator
     * @return Models
     */
    public function setServiceLocator(ServiceLocator $locator)
    {
        $this->locator = $locator;
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