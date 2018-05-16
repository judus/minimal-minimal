<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Demos\Events\EventModule;
use Maduser\Minimal\Framework\Application;
use Maduser\Minimal\Framework\Logger;
use Maduser\Minimal\Framework\Loader;
use Maduser\Minimal\Framework\Dispatcher;
use Maduser\Minimal\Framework\Responder;
use Maduser\Minimal\Framework\Terminator;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Providers\ConfigProvider;
use Maduser\Minimal\Framework\Providers\EventProvider;
use Maduser\Minimal\Framework\Providers\LoggerProvider;
use Maduser\Minimal\Framework\Providers\RequestProvider;
use Maduser\Minimal\Framework\Facades\IOC;

/**
 * Class ApplicationProvider
 *
 * @package Maduser\Minimal\Framework\Providers
 */
class ApplicationProvider extends AbstractProvider
{
    protected $basePath = '../';

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
     * @return ApplicationProvider
     */
    public function setBasePath(string $basePath): ApplicationProvider
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * @return mixed|void
     */
    public function resolve()
    {
        $args = func_get_args();

        return App::singleton('App', function () use ($args) {

            define('APPSTART', microtime(true));

            ! isset($args[0]) || extract($args[0]);

            ! isset($basepath) || $this->setBasePath($basepath);

            isset($getInstance) || $getInstance = false;

            $this->provide();
            $this->bind();
            $this->configure();
            $this->subscribe();
            $this->routes();
            $this->modules();

            return App::make(Application::class, [$args]);
        });
    }

    protected function getConfigDefaults()
    {
        return [
            'paths' => [
                'app' => 'app',
                'host' => 'localhost:8000',
                'modules' => 'app',
                'public' => 'public',
                'resources' => 'resources',
                'storage' => 'storage',
                'logs' => 'storage/logs',
                'system' => $this->getBasePath(),
                'translations' => 'storage/lang/lang.json',
                'views' => 'resources/views/my-theme'
            ],
            'database' => [
                'default' => [
                    'driver' => 'mysql',
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => '',
                    'password' => '',
                    'database' => '',
                    'charset' => 'utf8',
                    'handler' => \PDO::class,
                    'handlerOptions' => [
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false
                    ]
                ]
            ],
            'errors' => [
                'error_reporting' => 0,
                'display_errors' => 0
            ],
            'log' => [
                'level' => 4,
                'benchmarks' => false
            ],
            'storage' => [
                'app' => 'storage/app',
                'cache' => 'storage/cache',
                'logs' => 'storage/logs',
                'translation' => 'storage/translation'
            ],
        ];
    }

    /**
     * @param string $basePath
     */
    protected function configure()
    {
        Config::items($this->getConfigDefaults());
        Config::file($this->getBasePath() . 'config/environment.php');
    }

    /**
     *
     */
    protected function provide()
    {
      $file = $this->getBasePath() . 'config/providers.php';

      if (is_file($file)) {

          App::register(require_once $file);

        } else {

          App::register([
                'Assets' => AssetsProvider::class,
                'Collection' => CollectionProvider::class,
                'Config' => ConfigProvider::class,
                'ControllerFactory' => ControllerFactoryProvider::class,
                'Event' => EventProvider::class,
                'FormBuilder' => FormProvider::class,
                'FrontController' => FrontControllerProvider::class,
                'HtmlBuilder' => HtmlProvider::class,
                'Log' => LoggerProvider::class,
                'Middleware' => MiddlewareProvider::class,
                'Module' => ModuleProvider::class,
                'Modules' => ModulesProvider::class,
                'Request' => RequestProvider::class,
                'Response' => ResponseProvider::class,
                'Route' => RouteProvider::class,
                'Router' => RouterProvider::class,
                'View' => ViewProvider::class
            ]);
        }
    }

    /**
     *
     */
    protected function bind()
    {
        $file = $this->getBasePath() . 'config/bindings.php';

        if (is_file($file)) {

            App::bind(require_once $file);

        } else {

            App::bind([
                \Maduser\Minimal\Assets\Contracts\AssetsInterface::class                            => \Maduser\Minimal\Assets\Assets::class,
                \Maduser\Minimal\Collections\Contracts\CollectionInterface::class                   => \Maduser\Minimal\Collections\Collection::class,
                \Maduser\Minimal\Config\Contracts\ConfigInterface::class                            => \Maduser\Minimal\Config\Config::class,
                \Maduser\Minimal\Controllers\Factories\Contracts\ControllerFactoryInterface::class  => \Maduser\Minimal\Controllers\Factories\ControllerFactory::class,
                \Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface::class       => \Maduser\Minimal\Controllers\Factories\ModelFactory::class,
                \Maduser\Minimal\Modules\Contracts\ModulesInterface::class                          => \Maduser\Minimal\Modules\Modules::class,
                \Maduser\Minimal\Http\Contracts\ResponseInterface::class                            => \Maduser\Minimal\Http\Response::class,
                \Maduser\Minimal\Http\Contracts\RequestInterface::class                             => \Maduser\Minimal\Http\Request::class,
                \Maduser\Minimal\Provider\Contracts\ProviderInterface::class                        => \Maduser\Minimal\Provider\Provider::class,
                \Maduser\Minimal\Routing\Contracts\RouteInterface::class                            => \Maduser\Minimal\Routing\Route::class,
                \Maduser\Minimal\Routing\Contracts\RouterInterface::class                           => \Maduser\Minimal\Routing\Router::class,
                \Maduser\Minimal\Views\Contracts\ViewInterface::class                               => \Maduser\Minimal\Views\View::class,
            ]);
        }
    }

    /**
     *
     */
    protected function subscribe()
    {
        $file = $this->getBasePath() . 'config/subscribers.php';

        if (is_file($file)) {

            Event::register(require_once $file);

        } else {

            Event::register([
                App::make(Logger::class),
                App::make(Loader::class),
                App::make(Dispatcher::class),
                App::make(Responder::class),
                App::make(Terminator::class)
            ]);
        }
    }

    protected function routes()
    {
        $file = $this->getBasePath() . 'config/routes.php';

        is_file($file) && require_once $file;
    }

    protected function modules()
    {
        $file = $this->getBasePath() . 'config/modules.php';

        if (is_file($file)) {

            $modules = require_once $file;

            foreach ($modules as $module) {
                App::resolve('Modules')->register($module);
            }
        }
    }

}