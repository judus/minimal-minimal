<?php namespace Maduser\Minimal\Framework\Facades;

use Maduser\Minimal\Framework\Minimal as Implementation;

/**
 * Class App
 *
 * @package Maduser\Minimal\Framework\Facades
 */
class App extends Facade
{
    protected static $app;

    protected static $instance;

    protected static $baseDir;

    protected static $configDir;

    /**
     * Set the instance this facade refers to
     *
     * @param $instance
     */
    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    /**
     * Make the instance this facade refers to
     *
     * @param            $class
     * @param array|null $options
     *
     * @return Implementation
     */
    protected static function makeInstance(
        $class, array $options = null
    ): Implementation {

        $options || $options = ['provider' => self::app()];

        return IOC::make($class, [$options, true]);
    }

    public static function app()
    {
        return self::$app;
    }

    public static function use(string $app)
    {
        self::$app = $app;
        return self::class;
    }

    public static function baseDir(string $path = null)
    {
        !$path || self::$baseDir = $path;

        if (self::$baseDir) {
            return self::$baseDir;
        }

        return realpath(dirname($_SERVER["SCRIPT_FILENAME"]) . '/../') . '/';
    }

    public static function configDir(string $path = null)
    {
        !$path || self::$configDir = $path;

        if (self::$configDir) {
            return self::$configDir;
        }

        return self::baseDir() . 'config/';
    }

    /**
     * Get the instance this facade refers to
     *
     * @param null  $class
     * @param array $options
     *
     * @return Implementation
     */
    public static function getInstance(
        $class = null,
        array $options = []
    ) {
        /** @var Implementation $instance */
        if (is_null(self::$instance)) {
            if (is_null($class)) {
                self::$instance = self::makeInstance(
                    Implementation::class, $options
                )->getApp($options);

                self::$instance->load();
            } else {
                ! is_object($class) || self::$instance = $class;
            }
        }
        return self::$instance;
    }

    /**
     * Execute a route
     *
     * @param null $uri
     *
     * @return $this
     *
     */
    public static function run($uri = null)
    {
        return self::getInstance()->execute($uri)->getResults();
    }

    /**
     * Execute a route
     *
     * @param null $uri
     *
     * @return $this
     */
    public static function execute($uri = null)
    {
        return self::getInstance()->execute($uri)->getResults();
    }

    /**
     * Runs the application
     *
     * @param array         $options
     * @param \Closure|null $closure
     * @param null          $class
     */
    public static function dispatch(
        $options = [],
        $closure = null,
        $class = null
    ) {
        if (is_callable($options)) {
            $_closure = $options;
            $_options = $closure;
        } else {
            $_closure = $closure;
            $_options = $options;
        }

        is_array($_options) || $_options = [];

        /** @var Implementation $app */
        self::getInstance($class, $_options);

        is_null($_closure) || $_closure();

        self::getInstance()->dispatch();
    }

    /**
     * Return the bindings container
     *
     * @return \Maduser\Minimal\Provider\Container
     */
    public static function bindings()
    {
        return IOC::bindings();
    }

    /**
     * Return the providers container
     *
     * @return \Maduser\Minimal\Provider\Container
     */
    public static function providers()
    {
        return IOC::providers();
    }

    /**
     * Return a registered singleton or make one
     *
     * @param      $name
     * @param null $object
     *
     * @return mixed
     */
    public static function singleton($name, $object = null)
    {
        return IOC::singleton($name, $object);
    }

    /**
     * Add interface bindings
     *
     * @param array $bindings
     *
     * @return mixed
     */
    public static function bind(array $bindings)
    {
        return IOC::addBindings($bindings);
    }

    /**
     * Register providers/factories
     *
     * @param array $providers
     *
     * @return mixed
     */
    public static function register(array $providers)
    {
        return IOC::addProviders($providers);
    }

    /**
     * Instantiate a new class
     *
     * @param       $class
     * @param array $params
     *
     * @return mixed
     */
    public static function make($class, $params = [])
    {
        return IOC::make($class, $params);
    }

    /**
     * Resolve a class instance e.g. make or get singleton
     *
     * @param $name
     *
     * @return mixed
     */
    public static function  resolve($name, $params = [])
    {
        return IOC::resolve($name, $params);
    }
}