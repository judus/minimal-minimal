<?php namespace Maduser\Minimal\Framework\Apps\Minimal;

use Maduser\Minimal\Framework\AbstractApplication;
use Maduser\Minimal\Framework\ApplicationInterface;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Request;
use Maduser\Minimal\Http\Contracts\ResponseInterface;

class MinimalApplication extends AbstractApplication
{
    /**
     * Set by execute()
     *
     * @var mixed
     */
    public $results;

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

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
     * @param array|null $params
     *
     * @return ApplicationInterface
     */
    public function load(array $params = null): ApplicationInterface
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
    public function execute(string $uri = null, string $method = null): ApplicationInterface
    {
        /** @var \Maduser\Minimal\Routing\Contracts\RouteInterface $route */
        $route = App::resolve('Router')->getRoute($uri, $method);

        /** @var \Maduser\Minimal\Middlewares\Middleware $middleware */
        $middleware = App::resolve('Middleware', [$route->getMiddlewares()]);

        $this->results = $middleware->dispatch(function () use ($route, $uri) {

            if ($route->getDispatcher()) {
                return App::make($route->getDispatcher())->dispatch($route);
            }

            return App::resolve('Dispatcher')->dispatch($route);
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
        exit(0);
    }
}