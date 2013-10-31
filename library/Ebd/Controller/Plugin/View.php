<?php
/**
 * Standard Library
 *
 * @package Ebd_Controller
 * @subpackage Ebd_Controller_Plugin
 */

namespace Ebd\Controller\Plugin;

class View extends AbstractPlugin
{
    /**
     * Get View Object
     *
     * @return \Ebd\View\View
     * @todo remove TPL_DIR constant
     */
    public function __invoke()
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
}