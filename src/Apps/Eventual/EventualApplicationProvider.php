<?php namespace Maduser\Minimal\Framework\Apps\Eventual;

use Maduser\Minimal\Framework\AbstractApplicationProvider;
use Maduser\Minimal\Framework\Apps\Maximal\ArrayLoader;
use Maduser\Minimal\Framework\Apps\Maximal\Logger;
use Maduser\Minimal\Framework\Apps\Maximal\Loader;
use Maduser\Minimal\Framework\Apps\Maximal\EventualApplication;
use Maduser\Minimal\Framework\Apps\Maximal\Responder;
use Maduser\Minimal\Framework\Apps\Maximal\Terminator;
use Maduser\Minimal\Framework\Compiler;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Framework\Providers\AbstractProvider;
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
class EventualApplicationProvider extends AbstractApplicationProvider
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
     * @return SimpleAppProvider
     */
    public function setBasePath(string $basePath): SimpleAppProvider
    {
        $this->basePath = $basePath;

        return $this;
    }

    protected function basepath()
    {
        !(php_sapi_name() === 'cli' || defined('STDIN')) || $this->setBasePath('./');
    }

    public function register()
    {
        $this->basepath();
        $this->providers();
        $this->bindings();
        $this->configs();
        $this->subscribers();
        $this->routes();
        $this->modules();
    }

    /**
     * @return mixed|void
     */
    public function resolve()
    {
        $args = func_get_args();

        return App::singleton('App', function () use ($args) {

            define('APPSTART', microtime(true));

            !isset($args[0]) || extract($args[0]);

            return App::make(EventualApplication::class, [$args]);
        });
    }

    protected function config()
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
    protected function configs()
    {
        Config::items($this->config());
        Config::file($this->getBasePath() . 'config/environment.php');
    }

    /**
     *
     */
    protected function providers()
    {
        $file = $this->getBasePath() . 'config/providers.php';

        if (is_file($file)) {

            App::register(require_once $file);

        } else {

            App::register([
                'Assets' => \Maduser\Minimal\Framework\Providers\AssetsProvider::class,
                'Collection' => \Maduser\Minimal\Framework\Providers\CollectionProvider::class,
                'Config' => \Maduser\Minimal\Framework\Providers\ConfigProvider::class,
                'ControllerFactory' => \Maduser\Minimal\Framework\Providers\ControllerFactoryProvider::class,
                'Event' => \Maduser\Minimal\Framework\Providers\EventProvider::class,
                'FormBuilder' => \Maduser\Minimal\Framework\Providers\FormProvider::class,
                'FrontController' => \Maduser\Minimal\Framework\Providers\FrontControllerProvider::class,
                'HtmlBuilder' => \Maduser\Minimal\Framework\Providers\HtmlProvider::class,
                'Log' => \Maduser\Minimal\Framework\Providers\LoggerProvider::class,
                'Middleware' => \Maduser\Minimal\Framework\Providers\MiddlewareProvider::class,
                'Module' => \Maduser\Minimal\Framework\Providers\ModuleProvider::class,
                'Modules' => \Maduser\Minimal\Framework\Providers\ModulesProvider::class,
                'Request' => \Maduser\Minimal\Framework\Providers\RequestProvider::class,
                'Response' => \Maduser\Minimal\Framework\Providers\ResponseProvider::class,
                'Route' => \Maduser\Minimal\Framework\Providers\RouteProvider::class,
                'Router' => \Maduser\Minimal\Framework\Providers\RouterProvider::class,
                'View' => \Maduser\Minimal\Framework\Providers\ViewProvider::class
            ]);
        }
    }

    /**
     *
     */
    protected function bindings()
    {
        $file = $this->getBasePath() . 'config/bindings.php';

        if (is_file($file)) {

            App::bind(require_once $file);

        } else {

            App::bind([
                \Maduser\Minimal\Assets\Contracts\AssetsInterface::class => \Maduser\Minimal\Assets\Assets::class,
                \Maduser\Minimal\Collections\Contracts\CollectionInterface::class => \Maduser\Minimal\Collections\Collection::class,
                \Maduser\Minimal\Config\Contracts\ConfigInterface::class => \Maduser\Minimal\Config\Config::class,
                \Maduser\Minimal\Controllers\Factories\Contracts\ControllerFactoryInterface::class => \Maduser\Minimal\Controllers\Factories\ControllerFactory::class,
                \Maduser\Minimal\Controllers\Factories\Contracts\ModelFactoryInterface::class => \Maduser\Minimal\Controllers\Factories\ModelFactory::class,
                \Maduser\Minimal\Modules\Contracts\ModulesInterface::class => \Maduser\Minimal\Modules\Modules::class,
                \Maduser\Minimal\Http\Contracts\ResponseInterface::class => \Maduser\Minimal\Http\Response::class,
                \Maduser\Minimal\Http\Contracts\RequestInterface::class => \Maduser\Minimal\Http\Request::class,
                \Maduser\Minimal\Provider\Contracts\ProviderInterface::class => \Maduser\Minimal\Provider\Provider::class,
                \Maduser\Minimal\Routing\Contracts\RouteInterface::class => \Maduser\Minimal\Routing\Route::class,
                \Maduser\Minimal\Routing\Contracts\RouterInterface::class => \Maduser\Minimal\Routing\Router::class,
                \Maduser\Minimal\Views\Contracts\ViewInterface::class => \Maduser\Minimal\Views\View::class,
            ]);
        }
    }

    /**
     *
     */
    protected function subscribers()
    {
        $file = $this->getBasePath() . 'config/subscribers.php';

        if (is_file($file)) {

            Event::register(require_once $file);

        } else {

            Event::register([
                App::make(Logger::class),
                App::make(Loader::class),
                App::make(MinimalApplication::class),
                App::make(Responder::class),
                App::make(Compiler::class),
                App::make(Terminator::class)
            ]);
        }
    }

    protected function routes()
    {
        Router::get('/', function () {
            ob_start();
            phpinfo();
            $phpinfo = ob_get_contents();
            ob_clean();

            return '<h1 style="text-align: center">Minimal</h1>' . $phpinfo;
        });


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