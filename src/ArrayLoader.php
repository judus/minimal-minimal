<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Response;
use Maduser\Minimal\Framework\Facades\Router;

class ArrayLoader
{
    /**
     * Registers the minimal config file.
     * It stores the array items from the minimal config file in the Config
     * object and eventually sets the php.ini error_reporting and display_errors.
     *
     * @param string|null $filePath
     */
    public static function minimal(string $filePath = null)
    {
        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $configItems = require_once $filePath;

            !is_array($configItems) || Config::items($configItems);

            ini_set('error_reporting',
                Config::exists('errors.error_reporting', 0));
            ini_set('display_errors',
                Config::exists('errors.display_errors', 0));

            Event::dispatch('minimal.loaded.minimal', [
                $filePath,
                is_array($configItems) ? $configItems : []
            ]);
        }
    }

    /**
     * Registers the main config file.
     * It stores the array items from the main config file in the Config object.
     * It will merge recursively with other items in the Config object.
     * It eventually sets the php.ini error_reporting and display_errors
     *
     * @param string|null $filePath
     *
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public static function config(string $filePath = null)
    {
        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $configItems = require_once $filePath;

            if (is_array($configItems)) {
                foreach ($configItems as $key => $value) {
                    if (Config::exists($key)) {
                        Config::merge($key, $value);
                    } else {
                        Config::item($key, $value);
                    }
                }
            }

            if ($value = Config::exists('errors.error_reporting', null)) {
                ini_set('error_reporting', $value);
            }

            if ($value = Config::exists('errors.display_errors', null)) {
                ini_set('display_errors', $value);
            }

            Event::dispatch('minimal.loaded.config', [
                $filePath,
                is_array($configItems) ? $configItems : []
            ]);
        }
    }

    /**
     * Registers the bindings config file.
     * It adds the array items from the bindings config file to the IOC
     * container
     *
     * @param string|null $filePath
     */
    public static function bindings(string $filePath = null)
    {
        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $bindings = require_once $filePath;

            $bindings = is_array($bindings) ? $bindings : [];

            IOC::addBindings($bindings);

            Event::dispatch('minimal.loaded.bindings', [$filePath, $bindings]);
        }
    }

    /**
     * Registers the providers config file.
     * It adds the array items from the providers config file to the IOC
     * container.
     *
     * @param string|null $filePath
     *
     */
    public static function providers(string $filePath = null)
    {
        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $providers = require_once $filePath;

            $providers = is_array($providers) ? $providers : [];

            IOC::addProviders($providers);

            Event::dispatch('minimal.loaded.providers',
                [$filePath, $providers]);
        }
    }

    /**
     * Registers the event subscribers config file
     * It will make/resolve a instance of each subscriber class in the config
     * file and register it in the event dispatcher
     *
     * @param string|null $filePath
     */
    public static function subscribers(string $filePath = null)
    {
        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $subscribers = require_once $filePath;

            $subscribers = is_array($subscribers) ? $subscribers : [];

            foreach ($subscribers as $subscriber) {
                Event::register(IOC::resolve($subscriber));
            }

            Event::dispatch('minimal.loaded.subscribers',
                [$filePath, $subscribers]);
        }
    }

    /**
     * Registers the routes config file
     * It will just require the file e.g. execute the instructions in the file
     *
     * @param string|null $filePath
     */
    public static function routes(string $filePath = null)
    {
        if (is_file($filePath)) {
            require $filePath;

            Event::dispatch('minimal.loaded.routes', [$filePath]);
        }
    }

    /**
     * Registers the modules config file
     * It will just require the file e.g. execute the instructions in the file
     *
     * @param string|null $filePath
     */
    public static function modules(string $filePath = null)
    {
        if (is_file($filePath)) {

            /** @noinspection PhpIncludeInspection */
            $modules = require_once $filePath;

            $modules = is_array($modules) ? $modules : [];

            $Modules = IOC::resolve('Modules');

            foreach ($modules as $module) {
                $Modules->register($module);
            }

            Event::dispatch('minimal.loaded.modules', [$filePath, $modules]);
        }
    }
}