<?php

namespace Maduser\Minimal\Framework\Facades;

class Commands extends Facade
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
            self::$instance = IOC::resolve('Commands');
        }

        return self::$instance;
    }

    /**
     * Register one or several $commands
     *
     * @param string|array $commands
     *
     * @return mixed
     */
    public static function register($commands)
    {
        return self::call();
    }

    /**
     * Dispatch a command
     *
     * @param string $signature
     * @param array  $args
     *
     * @return mixed
     */
    public static function dispatch($signature, array $args = [])
    {
        return self::call();
    }

}