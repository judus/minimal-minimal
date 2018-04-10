<?php

namespace Maduser\Minimal\Framework\Facades;

use Maduser\Minimal\Event\Contracts\SubscriberInterface;
use Maduser\Minimal\Event\Dispatcher;

/**
 * Class Event
 *
 * @package Maduser\Minimal\Framework\Facades
 */
class Event extends Facade
{
    /**
     * The object this points to
     *
     * @var Dispatcher
     */
    protected static $instance;

    /**
     * Register a event subscriber
     *
     * @param $subscriber
     *
     * @return mixed
     */
    public static function register(SubscriberInterface $subscriber)
    {
        return self::call();
    }

    /**
     * Dispatch a event
     *
     * @param mixed event
     * @param mixed $data
     *
     * @return mixed
     */
    public static function dispatch($event, $data = null)
    {
        return self::call();
    }

    /**
     * Get the registered events
     *
     * @param mixed event
     * @param mixed $data
     *
     * @return mixed
     */
    public static function events()
    {
        return self::call();
    }
}