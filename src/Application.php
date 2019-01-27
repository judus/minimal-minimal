<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Modules;
use Maduser\Minimal\Framework\Facades\Response;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Provider\Contracts\ProviderInterface;

class Application extends Subscriber
{
    protected $container = [];

    protected $events = [];

    protected $results;

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

    public function __construct($params = null, $getInstance = false)
    {
        if (version_compare(phpversion(), '7.0.0', '<')) {
            die('Requires PHP version > 7.0.0');
        }
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
     * Shorthand method
     */
    public function dispatch()
    {
        if ((php_sapi_name() === 'cli' or defined('STDIN'))) {

            global $argv;

            new \Maduser\Minimal\Cli\Cli(array_slice($argv, 1), $this);

            $this->terminate();
        }

        $this->execute()->respond()->terminate();
    }

    /**
     * Dispatch load event
     *
     * @param array|null $files
     *
     * @return Application
     */
    public function load(array $files = null)
    {
        Event::dispatch('minimal.load', [$files]);

        return $this;
    }

    /**
     * Dispatch execute event
     *
     * @param null $uri
     *
     * @return Application
     */
    public function execute($uri = null)
    {
        Event::dispatch('minimal.execute', $uri);

        return $this;
    }

    /**
     * Dispatch respond event
     *
     * @return Application
     */
    public function respond()
    {
        Event::dispatch('minimal.respond', $this);

        return $this;
    }

    /**
     * Dispatch terminate event
     *
     * @return Application
     */
    public function terminate()
    {
        Event::dispatch('minimal.terminate', $this);

        return $this;
    }

}