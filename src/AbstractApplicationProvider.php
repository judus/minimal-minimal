<?php

namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Apps\Minimal\MinimalApplication;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Commands;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Framework\Providers\AbstractProvider;
use Maduser\Minimal\Provider\Contracts\AbstractProviderInterface;

/**
 * Class AbstractApplicationProvider
 *
 * @package Maduser\Minimal\Framework
 */
abstract class AbstractApplicationProvider extends AbstractProvider implements ApplicationProviderInterface
{
    /**
     * @var string
     */
    protected $applicationClass = MinimalApplication::class;

    /**
     * @var string
     */
    protected $basePath = '../';

    /**
     * @return string
     */
    public function getApplicationClass(): string
    {
        return $this->applicationClass;
    }

    /**
     * @param string $applicationClass
     *
     * @return AbstractApplicationProvider
     */
    public function setApplicationClass(string $applicationClass)
    {
        $this->applicationClass = $applicationClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     *
     * @return ApplicationProviderInterface
     */
    public function setBasePath(string $basePath): ApplicationProviderInterface
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     *
     */
    public function basepath()
    {
        if (php_sapi_name() === 'cli' || defined('STDIN')) {
            $this->setBasePath('./');
        }
    }

    /**
     * @return mixed|void
     */
    public function register()
    {
        $this->basepath();
        App::bind($this->bindings());
        App::register(['Config' => Providers\ConfigProvider::class]);
        Config::push($this->config());
        App::register($this->providers());
        Commands::register($this->commands());
        $this->routes();
    }

    /**
     * @return mixed
     */
    public function resolve()
    {
        $args = func_get_args();

        return App::singleton('App', function () use ($args) {

            define('APPSTART', microtime(true));

            !isset($args[0]) || extract($args[0]);

            return App::make($this->getApplicationClass(), [$args]);
        });
    }


    /**
     * @return array
     */
    public function config(): array
    {
        return [
            'paths' => [
                'system' => $this->getBasePath(),
            ],
            'errors' => [
                'error_reporting' => 0,
                'display_errors' => 0
            ],
        ];
    }

    /**
     *
     */
    public function providers(): array
    {
        return [
            'Collection' =>         Providers\CollectionProvider::class,
            'Config' =>             Providers\ConfigProvider::class,
            'ControllerFactory' =>  Providers\ControllerFactoryProvider::class,
            'FrontController' =>    Providers\FrontControllerProvider::class,
            'Middleware' =>         Providers\MiddlewareProvider::class,
            'Request' =>            Providers\RequestProvider::class,
            'Response' =>           Providers\ResponseProvider::class,
            'Route' =>              Providers\RouteProvider::class,
            'Router' =>             Providers\RouterProvider::class,
        ];
    }

    /**
     *
     */
    public function bindings(): array
    {
        return [
            \Maduser\Minimal\Collections\Contracts\CollectionInterface::class => \Maduser\Minimal\Collections\Collection::class,
            \Maduser\Minimal\Config\Contracts\ConfigInterface::class => \Maduser\Minimal\Config\Config::class,
            \Maduser\Minimal\Controllers\Factories\Contracts\ControllerFactoryInterface::class => \Maduser\Minimal\Controllers\Factories\ControllerFactory::class,
            \Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface::class => \Maduser\Minimal\Controllers\Factories\ModelFactory::class,
            \Maduser\Minimal\Http\Contracts\ResponseInterface::class => \Maduser\Minimal\Http\Response::class,
            \Maduser\Minimal\Http\Contracts\RequestInterface::class => \Maduser\Minimal\Http\Request::class,
            \Maduser\Minimal\Provider\Contracts\ProviderInterface::class => \Maduser\Minimal\Provider\Provider::class,
            \Maduser\Minimal\Routing\Contracts\RouteInterface::class => \Maduser\Minimal\Routing\Route::class,
            \Maduser\Minimal\Routing\Contracts\RouterInterface::class => \Maduser\Minimal\Routing\Router::class,
        ];
    }

    /**
     *
     */
    public function routes()
    {
        Router::get('/', function () {
            ob_start();
            phpinfo();
            $phpinfo = ob_get_contents();
            ob_clean();

            return '<h1 style="text-align: center">Minimal</h1>' . $phpinfo;
        });
    }

}

