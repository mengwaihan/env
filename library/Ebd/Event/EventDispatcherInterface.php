<?php
/**
 * Standard Library
 *
 * @package Ebd_Event
 */

namespace Ebd\Event;

interface EventDispatcherInterface
{
    public function addEventListener($name, $listener, $priority = 0);

    public function removeEventListener($name, $listener = null);

    public function dispatchEvent(EventInterface $event);
}