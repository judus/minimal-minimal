<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Event\Contracts\DispatcherInterface;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Collections\Contracts\CollectionInterface;
use Maduser\Minimal\Controllers\Contracts\FrontControllerInterface;
use Maduser\Minimal\Config\Contracts\ConfigInterface;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Http\Contracts\RequestInterface;
use Maduser\Minimal\Http\Contracts\ResponseInterface;
use Maduser\Minimal\Middlewares\Middleware;
use Maduser\Minimal\Modules\ModulesInterface;
use Maduser\Minimal\Routing\Contracts\RouterInterface;

/**
 * Class Minimal
 *
 * @package Maduser\Minimal\Core
 */
class Minimal implements AppInterface
{
    /**
     * The default path of the project directory relative to the index.php
     *
     * @var string
     */
    private $basePath = '..';

    /**
     * The default path of the app directory
     *
     * @var string
     */
    private $appPath = 'app';

    /**
     * The default path in which the modules resides
     *
     * @var string
     */
    private $modulesPath = 'app';

    /**
     * The default path of the minimal config file
     *
     * @var string
     */
    private $minimalFile = 'config/minimal.php';

    /**
     * The default path of the main config file
     *
     * @var string
     */
    private $configFile = 'config/environment.php';

    /**
     * The default path of the providers config file
     *
     * @var string
     */
    private $providersFile = 'config/providers.php';

    /**
     * The default path of the bindings config file
     *
     * @var string
     */
    private $bindingsFile = 'config/bindings.php';

    /**
     * The default path of the event subscribers config file
     *
     * @var string
     */
    private $subscribersFile = 'config/subscribers.php';

    /**
     * The default path of the routes config file
     *
     * @var string
     */
    private $routesFile = 'config/routes.php';

    /**
     * The default path of the modules config file
     *
     * @var string
     */
    private $modulesFile = 'config/modules.php';

    /**
     * An array to hold the objects that this class resolves through IOC
     *
     * @var array
     */
    private $container = [];

    /**
     * A reference to this
     *
     * @var AppInterface
     */
    private $app;

    /**
     * The registered modules collection
     *
     * @var CollectionInterface
     */
    private $modules;

    /**
     * Holds the return value of the FrontController
     *
     * @var mixed
     */
    private $result;

    /**
     * Returns a reference to this
     *
     * @return AppInterface
     */
    public function getApp(): AppInterface
    {
        return $this->app;
    }

    /**
     * Sets the reference to this
     *
     * @param AppInterface $app
     *
     * @return AppInterface
     */
    public function setApp(AppInterface $app): AppInterface
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Gets the path of the project directory relative to the index.php
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return rtrim($this->basePath, '/') . '/';
    }

    /**
     * Sets the path of the project directory relative to the index.php
     *
     * @param mixed $basepath
     *
     * @return AppInterface
     */
    public function setBasePath(string $basepath): AppInterface
    {
        $this->basePath = $basepath;

        return $this;
    }

    /**
     * Gets the path of the app directory
     *
     * @return string
     */
    public function getAppPath()
    {
        return rtrim($this->appPath, '/') . '/';
    }

    /**
     * Sets the path of the app directory
     *
     * @param mixed $appPath
     *
     * @return AppInterface
     */
    public function setAppPath(string $appPath): AppInterface
    {
        $this->appPath = $appPath;

        return $this;
    }

    /**
     * Gets the path of the modules directory
     *
     * @return string
     */
    public function getModulesPath()
    {
        return rtrim($this->modulesPath, '/') . '/';
    }

    /**
     * Sets the path of the modules directory
     *
     * @param string $filePath
     *
     * @return AppInterface
     */
    public function setModulesPath(string $filePath): AppInterface
    {
        $this->modulesPath = $filePath;

        return $this;
    }

    /**
     * Gets the path to the minimal config file
     *
     * @return string
     */
    public function getMinimalFile()
    {
        return $this->minimalFile;
    }

    /**
     * Sets the path to the minimal config file
     *
     * @param mixed $minimalFile
     *
     * @return Minimal
     */
    public function setMinimalFile($minimalFile)
    {
        $this->minimalFile = $minimalFile;

        return $this;
    }

    /**
     * Sets the path to the main config file
     *
     * @return mixed
     */
    public function getConfigFile(): string
    {
        return $this->configFile;
    }

    /**
     * Gets the path to the main config file
     *
     * @param string $path
     *
     * @return AppInterface
     */
    public function setConfigFile(string $path): AppInterface
    {
        $this->configFile = $path;

        return $this;
    }

    /**
     * Sets the path to the providers config file
     *
     * @return string
     */
    public function getProvidersFile(): string
    {
        return $this->providersFile;
    }

    /**
     * Gets the path to the providers config file
     *
     * @param string $path
     *
     * @return AppInterface
     */
    public function setProvidersFile(string $path): AppInterface
    {
        $this->providersFile = $path;

        return $this;
    }

    /**
     * Gets the path to the bindings config file
     *
     * @return string
     */
    public function getBindingsFile(): string
    {
        return $this->bindingsFile;
    }

    /**
     * Sets the path to the bindings config file
     *
     * @param string $path
     *
     * @return AppInterface
     */
    public function setBindingsFile(string $path): AppInterface
    {
        $this->bindingsFile = $path;

        return $this;
    }

    /**
     * Gets the path to the subscribers config file
     *
     * @return string
     */
    public function getSubscribersFile(): string
    {
        return $this->subscribersFile;
    }

    /**
     * Sets the path to the subscribers config file
     *
     * @param string $path
     *
     * @return Minimal
     */
    public function setSubscribersFile(string $path): AppInterface
    {
        $this->subscribersFile = $path;

        return $this;
    }

    /**
     * Gets the path to the routes config file
     *
     * @return string
     */
    public function getRoutesFile(): string
    {
        return $this->routesFile;
    }

    /**
     * Sets the path to the routes config file
     *
     * @param string $path
     *
     * @return AppInterface
     */
    public function setRoutesFile(string $path): AppInterface
    {
        $this->routesFile = $path;

        return $this;
    }

    /**
     * Gets the path to the modules config file
     *
     * @return string
     */
    public function getModulesFile()
    {
        return $this->modulesFile;
    }

    /**
     * Sets the path to the modules config file
     *
     * @param string $path
     *
     * @return AppInterface
     */
    public function setModulesFile(string $path): AppInterface
    {
        $this->modulesFile = realpath($path);

        return $this;
    }

    /**
     * Convinience method to get the Config object
     *
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        return $this->container('Config');
    }

    /**
     * Convinience method to get the Request object
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->container('Request');
    }

    /**
     * Convinience method to get the Response object
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->container('Response');
    }

    /**
     * Convinience method to get the Router object
     *
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->container('Router');
    }

    /**
     * Convinience method to get the EventDispatcher object
     *
     * @return DispatcherInterface
     */
    public function getEventDispatcher(): DispatcherInterface
    {
        return $this->container('Event');
    }

    /**
     * Convinience method to get the FrontController object
     *
     * @return FrontControllerInterface
     */
    public function getFrontController(): FrontControllerInterface
    {
        return $this->container('FrontController');
    }

    /**
     * Convinience method to get the Factory object
     *
     * @return ModulesInterface
     */
    public function getModules()
    {
        return $this->container('Modules');
    }

    /**
     * Get a Middleware instance with a list of middleware object to execute
     *
     * @param $middlewares
     *
     * @return Middleware
     */
    public function getMiddleware($middlewares)
    {
        return IOC::resolve('Middleware', [$middlewares]);
    }

    /**
     * Gets usually the result of a previously executed FrontController
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets what usually should be the result of executed FrontController
     *
     * @param mixed $result
     *
     * @return AppInterface
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Minimal constructor.
     *
     * @param array $params
     * @param bool  $returnInstance
     */
    public function __construct(array $params = [], $returnInstance = false)
    {
        define('APPSTART', microtime(true));

        if (version_compare(phpversion(), '7.0.0', '<')) {
            die('Requires PHP version > 7.0.0');
        }

        extract($params);

        !isset($basepath) || $this->setBasePath($basepath);

        defined('PATH') || define('PATH', $this->getBasePath());

        !isset($app) || $this->setAppPath($app);
        !isset($minimal) || $this->setMinimalFile($minimal);
        !isset($config) || $this->setConfigFile($config);
        !isset($providers) || $this->setProvidersFile($providers);
        !isset($bindings) || $this->setBindingsFile($bindings);
        !isset($subscribers) || $this->setSubscribersFile($subscribers);
        !isset($routes) || $this->setRoutesFile($routes);
        !isset($modules) || $this->setModulesFile($modules);

        $returnInstance || $this->dispatch();
    }

    /**
     * Gets an object from the container
     * Resolves it through IOC if that didn't happen yet
     *
     * @param $name
     *
     * @return mixed
     */
    public function container($name)
    {
        if (!isset($this->container[$name])) {
            $this->container[$name] = IOC::resolve($name);
        }

        return $this->container[$name];
    }

    /**
     * Registers the minimal config file.
     * It stores the array items from the minimal config file in the Config
     * object and eventually sets the phpini error_reporting and display_errors.
     *
     * @param null $filePath
     *
     * @return AppInterface
     */
    public function registerMinimal($filePath = null)
    {
        $filePath || $filePath = $this->getMinimalFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (file_exists($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $configItems = require_once $filePath;

            if (is_array($configItems)) {
                foreach ($configItems as $key => $value) {
                    $this->getConfig()->item($key, $value);
                }
            }

            if ($this->getConfig()->exists('errors.error_reporting')) {
                ini_set('error_reporting',
                    $this->getConfig()->item('errors.error_reporting'));
            }

            if ($this->getConfig()->exists('errors.display_errors')) {
                ini_set('display_errors',
                    $this->getConfig()->item('errors.display_errors'));
            }
            $this->event('minimal.loaded.minimal', [
                is_array($configItems) ? $configItems : [], $filePath, $this
            ]);
        }

        return $this;
    }

    /**
     * Registers the main config file.
     * It stores the array items from the main config file in the Config object.
     * It will merge recursively with other items in the Config object.
     * It eventually sets the phpini error_reporting and display_errors
     *
     * @param null $filePath
     *
     * @return AppInterface
     */
    public function registerConfig($filePath = null)
    {
        $filePath || $filePath = $this->getConfigFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (file_exists($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $configItems = require_once $filePath;

            if (is_array($configItems)) {
                foreach ($configItems as $key => $value) {
                    if ($this->getConfig()->exists($key)) {
                        $this->getConfig()->merge($key, $value);
                    } else {
                        $this->getConfig()->item($key, $value);
                    }
                }
            }

            if ($this->getConfig()->exists('errors.error_reporting')) {
                ini_set('error_reporting',
                    $this->getConfig()->item('errors.error_reporting'));
            }

            if ($this->getConfig()->exists('errors.display_errors')) {
                ini_set('display_errors',
                    $this->getConfig()->item('errors.display_errors'));
            }

            $this->event('minimal.loaded.config', [
                is_array($configItems) ? $configItems : [], $filePath, $this
            ]);
        }

        return $this;
    }

    /**
     * Registers the bindings config file.
     * It adds the array items from the bindings config file to the IOC
     * container
     *
     * @param null $filePath
     *
     * @return array
     */
    public function registerBindings($filePath = null): array
    {
        $filePath || $filePath = $this->getBindingsFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (file_exists($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $bindings = require_once $filePath;

            $bindings = is_array($bindings) ? $bindings : [];

            IOC::addBindings($bindings);

            $this->event('minimal.loaded.bindings', [
                $bindings, $filePath, $this
            ]);

            return $bindings;
        }

        return [];
    }

    /**
     * Registers the providers config file.
     * It adds the array items from the providers config file to the IOC
     * container.
     *
     * @param null $filePath
     *
     * @return AppInterface
     */
    public function registerProviders($filePath = null): array
    {
        $filePath || $filePath = $this->getProvidersFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $providers = require_once $filePath;

            $providers = is_array($providers) ? $providers : [];

            IOC::addProviders($providers);

            $this->event('minimal.loaded.providers', [
                $providers, $filePath, $this
            ]);

            return $providers;
        }

        return [];
    }

    /**
     * Registers the event subscribers config file
     * It will make/resolve a instance of each subscriber class in the config
     * file and register it in the event dispatcher
     *
     * @param $filePath
     *
     * @return array
     */
    public function registerSubscribers($filePath = null): array
    {
        $filePath || $filePath = $this->getSubscribersFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (file_exists($filePath)) {
            /** @noinspection PhpIncludeInspection */
            $subscribers = require_once $filePath;

            $subscribers = is_array($subscribers) ? $subscribers : [];

            foreach ($subscribers as $subscriber) {
                $this->getEventDispatcher()->register(IOC::resolve($subscriber));
            }

            $this->event('minimal.loaded.subscribers', [
                $subscribers, $filePath, $this
            ]);

            return $subscribers;
        }

        return [];
    }

    /**
     * Registers the routes config file
     * It will just require the file e.g. execute the instructions in the file
     *
     * @param $filePath
     *
     * @return AppInterface
     */
    public function registerRoutes($filePath = null): AppInterface
    {
        $filePath || $filePath = $this->getRoutesFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $router = $this->getRouter();

            /** @noinspection PhpUnusedLocalVariableInspection */
            $response = $this->getResponse();

            /** @noinspection PhpIncludeInspection */
            require $filePath;

            $this->event('minimal.loaded.routes', [
                $router, $filePath, $this
            ]);
        }

        return $this;
    }

    /**
     * Registers the modules config file
     * It will just require the file e.g. execute the instructions in the file
     *
     * @param $filePath
     *
     * @return array
     */
    public function registerModules($filePath = null): array
    {
        $filePath || $filePath = $this->getModulesFile();
        is_file($filePath) || $filePath = $this->getBasePath() . $filePath;

        if (is_file($filePath)) {
            /** @var Registry $modules */

            /** @noinspection PhpIncludeInspection */
            $modules = require_once $filePath;

            $modules = is_array($modules) ? $modules : [];

            foreach ($modules as $module) {
                $this->getModules()->register($module);
            }

            $this->event('minimal.loaded.modules', [
                $modules, $filePath, $this
            ]);
        }

        return $modules;
    }

    /**
     * The load/setup process of Minimal.
     * All that needs to be done before Minimal can be used as intended by
     * default.
     */
    public function load()
    {
        $this->event('minimal.load.before', $this);

        $this->registerBindings();
        $this->registerProviders();
        $this->registerMinimal();
        $this->registerConfig();
        $this->registerSubscribers();
        $this->registerRoutes();
        $this->registerModules();

        App::setInstance($this);

        $this->event('minimal.load.after', $this);

        return $this;
    }

    /**
     * Does what Minimal is here to do: Execute a route!
     *
     * @param null $uri
     *
     * @return $this
     */
    public function execute($uri = null)
    {
        $this->event('minimal.execute.before', $this);

        $route = $this->getRouter()->getRoute($uri);

        $this->event('minimal.route.found', [$route, $this]);

        $middleware = $this->getMiddleware($route->getMiddlewares());

        $this->event('minimal.middleware.dispatch.before', [
            $route, $middleware, $this
        ]);

        $response = $middleware->dispatch(function () use ($route, $uri) {

            $this->event('minimal.frontController.dispatch.before', [
                $route, $this
            ]);

            $results = $this->getFrontController()->dispatch($route)->getResult();

            $this->event('minimal.frontController.dispatch.after', [
                $results, $route, $this
            ]);

            return $results;
        });

        $this->event('minimal.middleware.dispatch.after', [
            $response, $route, $middleware, $this
        ]);

        $this->setResult($response);

        $this->event('minimal.execute.after', [
            $response, $route, $middleware, $this
        ]);

        return $this;
    }

    /**
     * Sets the content in the Response object and tells the Response object
     * to send it.
     *
     * @return $this
     */
    public function respond()
    {
        $this->event('minimal.respond.before', $this);
        $this->getResponse()->setContent($this->getResult())->send();
        $this->event('minimal.respond.after', $this);

        return $this;
    }

    /**
     * Exit php
     * Eventually do something just before that?
     */
    public function terminate()
    {
        $this->event('minimal.terminate', $this);
        $this->event('minimal.terminated', $this);
        exit();
    }

    /**
     * Executes all the 4 stages: load, execute, respond, terminate
     */
    public function dispatch()
    {
        $this->load();

        $this->event('minimal.dispatch.before', $this);

        $this->execute();
        $this->respond();

        $this->event('minimal.dispatch.after', $this);

        $this->terminate();
    }

    protected $hasEventDispatcher = false;

    protected function hasEventDispatcher()
    {
        if (! $this->hasEventDispatcher) {
            $this->hasEventDispatcher = IOC::hasProvider('Event');
        }

        return $this->hasEventDispatcher;
    }

    protected function event($event, $data = null)
    {
        if ($this->hasEventDispatcher()) {
            return Event::dispatch($event, $data);
        }

        return [];
    }
}