<?php namespace Maduser\Minimal\Framework\Apps\Eventual;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\AbstractApplication;
use Maduser\Minimal\Framework\ApplicationInterface;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Modules;
use Maduser\Minimal\Framework\Facades\Response;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Provider\Contracts\ProviderInterface;

class EventualApplication implements ApplicationInterface
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
        if (php_sapi_name() === 'cli' || defined('STDIN')) {

            global $argv;

            App::make('Cli', [array_slice($argv, 1), $this]);

            $this->terminate();
        }

        $this->execute()->respond()->terminate();
    }

    /**
     * Dispatch load event
     *
     * @param array|null $files
     *
     * @return ApplicationInterface
     */
    public function load(array $files = null) : ApplicationInterface
    {
        Event::dispatch('minimal.load', [$files]);

        return $this;
    }

    /**
     * Dispatch execute event
     *
     * @param string|null $uri
     *
     * @return ApplicationInterface
     */
    public function execute(string $uri = null): ApplicationInterface
    {
        Event::dispatch('minimal.execute', $uri);

        return $this;
    }

    /**
     * Dispatch respond event
     *
     * @return ApplicationInterface
     */
    public function respond(): ApplicationInterface
    {
        Event::dispatch('minimal.respond', $this);

        return $this;
    }

    /**
     * Dispatch terminate event
     *
     * @return ApplicationInterface
     */
    public function terminate(): ApplicationInterface
    {
        Event::dispatch('minimal.terminate', $this);

        return $this;
    }

}