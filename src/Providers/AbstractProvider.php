<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Provider\Contracts\AbstractProviderInterface;

/**
 * Class Provider
 *
 * @package Maduser\Minimal\Providers
 */
abstract class AbstractProvider implements AbstractProviderInterface
{
    /**
     * Called after pushing a service provider to the container
     */
    public function register()
    {
        $bindings = $this->bindings();
        count($bindings) > 0 && App::bind($bindings);

        $providers = $this->providers();
        count($providers) > 0 && App::register($providers);

        $config = $this->config();
        count($config) > 0 && Config::push($config);

        $subscribers = $this->subscribers();
        count($subscribers) > 0 && Event::register($subscribers);

        $this->routes();
    }

    public function config(): array
    {
        return [];
    }

    public function bindings(): array
    {
        return [];
    }

    public function providers(): array
    {
        return [];
    }

    public function subscribers(): array
    {
        return [];
    }

    public function routes()
    {
        return [];
    }

    /**
     * Called when resolving a service from the container
     */
    public function resolve() {}

    /**
     * Tells the container the service should be a singleton
     *
     * @param $name
     * @param $object
     *
     * @return mixed
     */
    public function singleton($name, $object)
    {
        if (IOC::hasSingleton($name)) {
            return IOC::singleton($name);
        } else {
            IOC::singleton($name, $object);
            return $object;
        }
    }

    /**
     *
     */
    public function __toString()
    {
        return get_class($this);
    }

}