<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 */

namespace Ebd\View\Helper;

use Ebd\View\Renderer\RendererInterface;

interface HelperInterface
{
    /**
     * Set view object
     *
     * @param RendererInterface $renderer
     * @return HelperInterface
     */
    public function setView(RendererInterface $renderer);

    /**
     * @return RendererInterface
     */
    public function getView();
}
