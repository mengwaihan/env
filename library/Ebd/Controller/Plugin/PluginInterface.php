<?php
/**
 * Standard Library
 *
 * @package Ebd_Controller
 */

namespace Ebd\Controller\Plugin;

use Ebd\Controller\AbstractActionController;

interface PluginInterface
{
    /**
     * Set controller
     *
     * @param AbstractActionController $controller
     * @return PluginInterface
     */
    public function setController(AbstractActionController $controller);

    /**
     * Get controller
     *
     * @return AbstractActionController
     */
    public function getController();
}
