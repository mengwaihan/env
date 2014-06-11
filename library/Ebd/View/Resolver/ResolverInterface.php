<?php
/**
 * Standard Library
 *
 * @package Ebd_View
 */

namespace Ebd\View\Resolver;

interface ResolverInterface
{
    /**
     * Resolve a template/pattern name to a resource the renderer can consume
     *
     * @param string $name
     * @return mixed
     */
    public function resolve($name);
}
