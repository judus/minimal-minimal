<?php namespace Maduser\Minimal\Framework\Facades;

use Maduser\Minimal\Framework\Minimal as Implementation;

/**
 * Class App
 *
 * @package Maduser\Minimal\Framework\Facades
 */
class App extends Facade
{
    protected static $instance;

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
        $class,
        array $options = null
    ): Implementation {
        // TODO: get rid of $_SERVER
        $options || $options = [
            'path' => realpath($_SERVER['DOCUMENT_ROOT'] . '/../') . '/'
        ];

        return IOC::make($class, [$options, true]);
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
    ): Implementation {

        /** @var Implementation $instance */
        if (is_null(self::$instance)) {
            if (is_null($class)) {
                self::$instance = self::makeInstance(
                    Implementation::class, $options
                );

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
        return self::getInstance()->execute($uri)->getResult();
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
        return self::getInstance()->execute($uri)->getResult();
    }

    /**
     * Runs the application
     *
     * @param array         $options
     * @param \Closure|null $closure
     * @param null          $class
     */
    public static function respond(
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

        self::getInstance()->execute()->respond()->terminate();
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
    public static function resolve($name)
    {
        return IOC::resolve($name);
    }
}