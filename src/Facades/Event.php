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
     * @return mixed
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            self::$instance = IOC::resolve('Event');
        }

        return self::$instance;
    }

    /**
     * Register one or several event subscribers
     *
     * @param string|array $subscribers
     *
     * @return mixed
     */
    public static function register($subscribers)
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