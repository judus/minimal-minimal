<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Collections\Collection;
use Maduser\Minimal\Framework\Apps\Maximal\MaximalApplicationProvider;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Facades\Commands;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Routing\Contracts\RouterInterface;

/**
 * Class Minimal
 *
 * @package Maduser\Minimal\Framework
 */
class Minimal implements AppInterface
{
    /**
     * @var string
     */
    protected $provider = MaximalApplicationProvider::class;

    /**
     * @var 
     */
    protected $app;

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     *
     * @return Minimal
     */
    public function setProvider(string $provider): Minimal
    {
        $this->provider = $provider;

        return $this;
    }
    
    /**
     * Minimal constructor.
     *
     * @param array|null $params
     */
    public function __construct(array $params = null)
    {
        extract($params);

        isset($provider) || $provider = $this->getProvider();

        isset($mode) || $mode = 'development';

        $mode == 'production' ?
            $this->compile($provider) : IOC::addProviders(['App' => $provider]);
    }

    /**
     * @return mixed
     */
    public function getApp()
    {
        $args = func_get_args();
        $args = count($args) > 0 ? $args[0] : null;

        return IOC::resolve('App', $args);
    }

    public function compile($provider)
    {
        if (file_exists('compiled.php')) {
            $this->compiled(require_once 'compiled.php');
            return;
        }

        IOC::addProviders(['App' => $provider]);

        $configs = $this->state();

        $content = "<?php\n" . '$config = ' . var_export($configs, true) . ";\n";

        $content .= "\n" . 'return $config;';

        $this->toFile($content);
    }

    public function toFile($content)
    {
        $path = 'compiled.php';
        file_put_contents('compiled.php', $content, LOCK_EX);
    }

    public function state()
    {
        return [
            'config' => Config::items(),
            'bindings' => App::bindings()->getArray(),
            'providers' => $this->providers()->getArray(),
            'subscribers' => $this->events()->getArray(),
            'routes' => $this->routes(),
        ];
    }

    public function compiled(array $compiled)
    {
        IOC::compile(true);

        IOC::setBindings($compiled['bindings']);
        IOC::setProviders($compiled['providers']);
        Config::items($compiled['config']);

        /** @var RouterInterface $router */
        $router = IOC::resolve('Router');
        foreach($compiled['routes'] as $route){
            $router->register(
                $route['requestMethod'],
                $route['uriPrefix'] . $route['uriPattern'],
                $route
            );
        }

    }

    public function providers()
    {
        $providers = App::providers()->each(function ($item) {
            return is_object($item) ? get_class($item) : $item;
        });

        return $providers;
    }

    public function events()
    {
        $events = Collection::create(App::resolve('Event')->events());
        $events = $events->each(function ($event, $subscribers) {
            $actions = [];
            foreach ($subscribers as $subscriber) {
                /** @var SubscriberInterface $subscriber */
                $eventActions = $subscriber->getEventActions($event);
                foreach ($eventActions as $ea) {
                    $actions[$event][get_class($subscriber)][] = $ea;
                }
            }

            return $actions[$event];
        });

        return $events;
    }

    public function commands()
    {
        /** @var Comm $commands */
        $commands = Commands::getInstance();
    }

    public function routes()
    {
        $routes = IOC::resolve('Router')->getRoutes();

        return $routes->get('ALL')->toArray();
    }
}