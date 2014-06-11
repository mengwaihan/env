<?php
/**
 * Standard Library
 *
 * @package Ebd_Router
 */

namespace Ebd\Router;

interface RouterInterface
{
    public function createUrl($path, array $params);
    public function parseUrl($requestUrl = null);
}
