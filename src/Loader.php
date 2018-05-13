<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\ArrayLoader;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Response;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Framework\Facades\Modules;

class Loader extends Subscriber
{
    protected $events = [
        'minimal.load' => 'load'
    ];

    protected $isLoaded = false;

    /**
     * @param array|null $files
     *
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    protected function load(array $files = null)
    {
        $defaults = [
            'bindings' => null,
            'providers' => null,
            'minimal' => null,
            'config' => null,
            'subscribers' => null,
            'routes' => null,
            'modules' => null
        ];

        is_array($files) ? extract($files) : extract($defaults);

        $bindings = isset($bindings) ? $bindings : null;
        ArrayLoader::bindings(Config::paths('system') . $bindings);

        $providers = isset($providers) ? $providers : null;
        ArrayLoader::providers(Config::paths('system') . $providers);

        $minimal = isset($minimal) ? $minimal : null;
        ArrayLoader::config(Config::paths('system') . $minimal);

        $config = isset($config) ? $config : null;
        ArrayLoader::config(Config::paths('system') . $config);

        $subscribers = isset($subscribers) ? $subscribers : null;
        ArrayLoader::subscribers(Config::paths('system') . $subscribers);

        $routes = isset($routes) ? $routes : null;
        ArrayLoader::routes(Config::paths('system') . $routes);

        $modules = isset($modules) ? $modules : null;
        ArrayLoader::modules(Config::paths('system') . $modules);

    }

    /**
     * Registers the minimal config file.
     * It stores the array items from the minimal config file in the Config
     * object and eventually sets the phpini error_reporting and display_errors.
     *
     * @param string|null $filePath
     */
    public function registerMinimal(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

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
     * It eventually sets the phpini error_reporting and display_errors
     *
     * @param null $filePath
     *
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function registerConfig(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

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

            Event::dispatch('minimal.loaded.config', [$filePath,
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
    public function registerBindings(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

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
    public function registerProviders(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $providers = require_once $filePath;

            $providers = is_array($providers) ? $providers : [];

            IOC::addProviders($providers);

            Event::dispatch('minimal.loaded.providers', [$filePath, $providers]);
        }
    }

    /**
     * Registers the event subscribers config file
     * It will make/resolve a instance of each subscriber class in the config
     * file and register it in the event dispatcher
     *
     * @param string|null $filePath
     */
    public function registerSubscribers(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $subscribers = require_once $filePath;

            $subscribers = is_array($subscribers) ? $subscribers : [];

            foreach ($subscribers as $subscriber) {
                Event::register(IOC::resolve($subscriber));
            }

            Event::dispatch('minimal.loaded.subscribers', [$filePath, $subscribers]);
        }
    }

    /**
     * Registers the routes config file
     * It will just require the file e.g. execute the instructions in the file
     *
     * @param string|null $filePath
     */
    public function registerRoutes(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $router = Router::getInstance();

            /** @noinspection PhpUnusedLocalVariableInspection */
            $response = Response::getInstance();

            /** @noinspection PhpIncludeInspection */
            require $filePath;

            Event::dispatch('minimal.loaded.routes', [$filePath, $router]);
        }
    }

    /**
     * Registers the modules config file
     * It will just require the file e.g. execute the instructions in the file
     *
     * @param string|null $filePath
     */
    public function registerModules(string $filePath = null)
    {
        is_file($filePath) || $filePath = App::getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @var Registry $modules */

            /** @noinspection PhpIncludeInspection */
            $modules = require_once $filePath;

            $modules = is_array($modules) ? $modules : [];

            foreach ($modules as $module) {
                IOC::resolve('Modules')->register($module);
            }

            Event::dispatch('minimal.loaded.modules', [$filePath, $modules]);
        }
    }

}