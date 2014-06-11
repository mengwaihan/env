<?php
/**
 * Standard Library
 *
 * @package Ebd_Utils
 */

namespace Ebd\Utils;

class String
{
    /**
     * @param string $str
     * @param string $seperator
     * @return string
     * @example
     *  $url = $url . String::concat($query, '?')
     *  $sql = $sql . String::concat('LIMIT 0, 1')
     */
    public static function concat($str, $seperator = ' ')
    {
        if (strval($str) === '') {
            return '';
        }
        return $seperator . $str;
    }
}