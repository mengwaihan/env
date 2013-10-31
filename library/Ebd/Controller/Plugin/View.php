<?php
/**
 * Standard Library
 *
 * @package Ebd_Controller
 * @subpackage Ebd_Controller_Plugin
 */

namespace Ebd\Controller\Plugin;

use Ebd\View\Model\ModelInterface;
use Ebd\View\Model\ViewModel;
use Ebd\Loader\Autoloader;
use Ebd\ServiceLocator\ServiceLocatorAwareInterface;
use Ebd\ServiceLocator\ServiceLocator;

/**
 * Get the view model from other method of controller
 */
class View extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocator
     */
    protected $locator = null;

    /**
     * Saved models
     *
     * @var array
     */
    protected $models = array();

    /**
     * Get a model from the method of controller
     * It also can pass some parameters.
     *
     * @param string|array $method
     * @param mixed $more_parameters
     * @return ModelInterface
     */
    public function __invoke()
    {
        /* @var $resolver ViewResolver */
        $resolver = $this->locator->get('Ebd\View\Resolver\Resolver');
        $resolver->addPath(TPL_DIR);

        /* @var $renderer PhpRenderer */
        $renderer = $this->locator->get('Ebd\View\Renderer\PhpRenderer');
        $renderer->setResolver($resolver);

        /* @var $view View */
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