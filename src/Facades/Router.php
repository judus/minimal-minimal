<?php namespace Maduser\Minimal\Framework\Facades;

use Maduser\Minimal\Collections\Contracts\CollectionInterface;
use Maduser\Minimal\Routing\Contracts\RouteInterface;
use Maduser\Minimal\Routing\Contracts\RouterInterface;

/**
 * Class Router
 *
 * @package Maduser\Minimal\Framework\Facades
 */
class Router extends Facade
{
    protected static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = IOC::resolve('Router');
        }

        return self::$instance;
    }

    /**
     * @param          $uriPattern
     * @param \Closure $callback
     *
     * @return mixed
     */
    public static function group($uriPattern, \Closure $callback)
    {
        return self::call();
    }

    /**
     * @param $uriPattern
     * @param $action
     *
     * @return RouteInterface
     */
    public static function get($uriPattern, $action)
    {
        return self::call();
    }
    
    /**
     * @param $uriPattern
     * @param $action
     *
     * @return RouteInterface
     */
    public static function post($uriPattern, $action)
    {
        return self::call();
    }
    
    /**
     * @param $uriPattern
     * @param $action
     *
     * @return RouteInterface
     */
    public static function put($uriPattern, $action)
    {
        return self::call();
    }

    /**
     * @param $uriPattern
     * @param $action
     *
     * @return RouteInterface
     */
    public static function patch($uriPattern, $action)
    {
        return self::call();
    }

    /**
     * @param $uriPattern
     * @param $action
     *
     * @return RouteInterface
     */
    public static function delete($uriPattern, $action)
    {
        return self::call();
    }

    /**
     * @param string $requestMethod
     *
     * @return CollectionInterface
     */
    public static function all($requestMethod = 'ALL'): CollectionInterface
    {
        return self::call();
    }

    /**
     * @param null $uriString
     *
     * @return RouteInterface
     */
    public static function getRoute($uriString = null): RouteInterface
    {
        return self::call();
    }

    /**
     * @return CollectionInterface
     */
    public static function getRoutes(): CollectionInterface
    {
        return self::call();
    }

    /**
     * @param bool|null $bool
     *
     * @return bool
     */
    public static function isClosure(bool $bool = null): bool
    {
        return self::call();
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    public static function uri(string $name, array $params = [])
    {
        return self::call();
    }
}