<?php namespace Maduser\Minimal\Framework\Facades;

use ReflectionClass;

/**
 * Class Facade
 *
 * @package Maduser\Minimal\Framework\Facades
 */
abstract class Facade
{
    /**
     * @var
     */
    protected static $instance;

     /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(
            [static::getInstance(), $name], $arguments);
    }

    /**
     * @return mixed
     */
    public static function call()
    {
        $name = debug_backtrace()[1]['function'];
        $arguments = debug_backtrace()[1]['args'];

        return call_user_func_array(
            [static::getInstance(), $name], $arguments);
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            $reflect = new ReflectionClass(get_called_class());
            $class = App::getInstance();
            $method = 'get' . $reflect->getShortName();
            if (method_exists($class, $method)) {
                self::$instance = $class->{$method}();
            } else {
                self::$instance = $class->container($reflect->getShortName());
            }
        }

        return self::$instance;
    }
}