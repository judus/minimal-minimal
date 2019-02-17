<?php namespace Maduser\Minimal\Framework\Apps\Minimal;

use Maduser\Minimal\Framework\AbstractApplication;
use Maduser\Minimal\Framework\ApplicationInterface;
use Maduser\Minimal\Framework\Facades\App;

class MinimalApplication extends AbstractApplication
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
     */
    public function load(): ApplicationInterface
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
    public function execute(string $uri = null): ApplicationInterface
    {
        /** @var \Maduser\Minimal\Routing\Contracts\RouteInterface $route */
        $route = App::resolve('Router')->getRoute($uri);

        /** @var \Maduser\Minimal\Middlewares\Middleware $middleware */
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
    public function respond(): ApplicationInterface
    {
        App::resolve('Response')->setContent($this->results)->send();

        return $this;
    }

    /**
     * May be used to exit PHP or do other termination things
     */
    public function terminate()
    {
    }
}