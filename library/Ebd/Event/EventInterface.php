<?php
/**
 * Standard Library
 *
 * @package Ebd_Event
 */

namespace Ebd\Event;

interface EventInterface
{
    public function setName($name);
    public function getName();

    public function setTarget($target);
    public function getTarget();

    public function setParams($params);
    public function getParams();

    public function setParam($name, $value);
    public function getParam($name, $default = null);

    /**
     * @todo
     */
    //public function preventDefault();
    //public function stopPropagation();
}