<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

class Urlencode
{
    /**
     * @param string $str
     * @param string $raw
     * @return string
     */
    public function __invoke($str, $raw = true)
    {
        return $raw ? rawurlencode($str) : urlencode($str);
    }
}