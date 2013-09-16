<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

use Ebd\View\Renderer\RendererInterface;

abstract class AbstractHelper implements HelperInterface
{
    /**
     * @var RendererInterface
     */
    protected $view = null;

    /**
     * Set view object
     *
     * @param RendererInterface $renderer
     * @return HelperInterface
     */
    public function setView(RendererInterface $view)
    {
        $this->view = $view;
    }

    /**
     * @return RendererInterface
     */
    public function getView()
    {
        return $this->view;
    }
}