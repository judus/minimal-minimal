<?php

namespace Maduser\Minimal\Framework\Facades;

use Maduser\Minimal\Log\Logger;
/**
 * Class Event
 *
 * @package Maduser\Minimal\Framework\Facades
 */
class Log extends Facade
{
    /**
     * The object this points to
     *
     * @var Logger
     */
    protected static $instance;

    public static function setDir(string $path)
    {
        return self::call();
    }

    public static function debug(string $msg, $data = null)
    {
        return self::call();
    }

    public static function system(string $msg, $data = null)
    {
        return self::call();
    }

    public static function info(string $msg, $data = null)
    {
        return self::call();
    }

    public static function warn(string $msg, $data = null)
    {
        return self::call();
    }

    public static function error(string $msg, $data = null)
    {
        return self::call();
    }

    public static function log(int $level, string $msg, $data = null)
    {
        return self::call();
    }
}