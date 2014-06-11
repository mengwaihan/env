<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 * @subpackage Ebd_View_Helper
 */

namespace Ebd\View\Helper;

class Date
{
    /**
     * format date
     *
     * @param string|int $date
     * @param string $format
     * @param string $default
     * @return string
     */
    public function __invoke($date, $format = 'Y-m-d H:i:s', $default = null)
    {
        if (empty($date) || '0000-00-00' === $date || '0000-00-00 00:00:00' === $date) {
            return $default;
        }

        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return date($format, $timestamp);
    }
}
