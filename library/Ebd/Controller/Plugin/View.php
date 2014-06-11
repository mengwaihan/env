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
use Ebd\ServiceLocator\PluginManager\FactoryInterface;

class View extends AbstractPlugin implements ServiceLocatorAwareInterface, FactoryInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * Get View Object
     *
     * @return \Ebd\View\View
     * @todo remove TPL_DIR constant
     */
    public function factory()
    {
        /* @var $resolver \Ebd\View\Resolver\Resolver */
        $resolver = $this->locator->get('Ebd\View\Resolver\Resolver');
        $resolver->addPath(TPL_DIR);

        /* @var $renderer \Ebd\View\Renderer\PhpRenderer */
        $renderer = $this->locator->get('Ebd\View\Renderer\PhpRenderer');
        $renderer->setResolver($resolver);

        /* @var $view \Ebd\View\View */
        $view = $this->locator->get('Ebd\View\View');
        $view->setRender($renderer);

        // return
        return $view;
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