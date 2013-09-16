<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

use Ebd\Utils\Url as UtilsUrl;

class SelfUrl
{
    /**
     * Get current url
     * It is almost same with $_SERVER['REQUEST_URI']
     *
     * @param array|string|null $query
     * @param boolean $escape
     * @return string
     */
    public function __invoke($query = null, $escape = true)
    {
        $url = UtilsUrl::buildUrl($query, $_SERVER['REQUEST_URI']);
        return $escape ? htmlspecialchars($url) : $url;
    }
}