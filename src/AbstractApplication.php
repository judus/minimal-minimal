<?php

namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Middlewares\Middleware;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * Set by execute()
     *
     * @var mixed
     */
    public $results;

    /**
     * Executes, responds and terminates
     */
    public function dispatch()
    {
        $this->execute()->respond()->terminate();
    }

    /**
     * May be used to load something before execution
     *
     * @param array|null $files
     *
     * @return ApplicationInterface
     */
    public function load(array $files = null): ApplicationInterface
    {
        return $this;
    }

    /**
     * Executes instructions from route
     *
     * @param string|null $uri
     *
     * @return ApplicationInterface
     */
    public function execute(string $uri = null) : ApplicationInterface
    {
        /** @var RouteInterface $route */
        $route = App::resolve('Router')->getRoute($uri);

        /** @var Middleware $middleware */
        $middleware = App::resolve('Middleware', [$route->getMiddlewares()]);

        $this->results = $middleware->dispatch(function () use ($route, $uri) {
            return App::resolve('FrontController')->dispatch($route)->getResult();
        });

        return $this;
    }

    /**
     * Sends the response to client
     *
     * @return ApplicationInterface
     */
    public function respond() : ApplicationInterface
    {
        App::resolve('Response')->setContent($this->results)->send();

        return $this;
    }

    /**
     * May be used to exit PHP or do other termination things
     */
    public function terminate() {}

}