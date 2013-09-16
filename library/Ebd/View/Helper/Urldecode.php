<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

class Urldecode
{
    /**
     * @param string $str
     * @param boolean $raw
     * @return string
     */
    public function __invoke($str, $raw = true)
    {
        return $raw ? rawurldecode($str) : urldecode($str);
    }
}