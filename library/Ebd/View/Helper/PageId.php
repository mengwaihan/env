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

class PageId implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * @var string
     */
    protected $pageId = null;

    /**
     * format date
     *
     * @param string|int $date
     * @param string $format
     * @param string $default
     * @return string
     */
    public function __invoke()
    {
        if (null === $this->pageId) {
            return $this->pageId = $this->locator->get('ControllerName') . '-' . $this->locator->get('ActionName');
        }
        return $this->pageId;
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
