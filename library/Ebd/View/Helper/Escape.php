<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

class Escape
{
    /**
     * Escape the html string
     *
     * @param string $str
     * @return string
     */
    public function __invoke($str)
    {
        return htmlspecialchars($str, ENT_COMPAT | ENT_XHTML);
    }
}