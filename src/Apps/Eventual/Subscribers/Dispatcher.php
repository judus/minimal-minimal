<?php namespace Maduser\Minimal\Framework\Apps\Eventual\Subscribers;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Router;

class Dispatcher extends Subscriber
{
    protected $events = [
        'minimal.execute' => 'execute'
    ];

    protected function execute(string $uri = null)
    {

        Event::dispatch('minimal.execute.before');

        $route = Router::getRoute($uri);

        Event::dispatch('minimal.route.found', $route);

        $middleware = IOC::resolve('Middleware', [$route->getMiddlewares()]);

        Event::dispatch('minimal.middleware.dispatch.before', [$route, $middleware]);

        $results = $middleware->dispatch(function () use ($route, $uri) {

            Event::dispatch('minimal.frontController.dispatch.before', [$route]);

            $results = IOC::resolve('FrontController')->dispatch($route)->getResult();

            Event::dispatch('minimal.frontController.dispatch.after', [$results, $route]);

            return $results;

        });

        Event::dispatch('minimal.middleware.dispatch.after', [$results, $route, $middleware]);

        App::setResults($results);

        Event::dispatch('minimal.execute.after', [$results, $route, $middleware]);
    }
}