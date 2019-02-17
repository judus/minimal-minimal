<?php namespace Maduser\Minimal\Framework\Apps\Maximal;

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

class MaximalApplication extends AbstractApplication
{
    protected $container = [];

    protected $events = [];

    public $results;

    protected $onLoad;

    protected $onExecute;

    protected $onRespond;

    protected $onTerminate;

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

    public function setOnLoad(\Closure $closure)
    {
        $this->onLoad = $closure;
    }

    public function setOnExecute(\Closure $closure)
    {
        $this->onExecute = $closure;
    }

    public function setOnRespond(\Closure $closure)
    {
        $this->onRespond = $closure;
    }

    public function setOnTerminate(\Closure $closure)
    {
        $this->onTerminate = $closure;
    }

    public function callLoad($params = null)
    {
        return $this->makeCall($this->onLoad, $params);
    }

    public function callExecute($params = null)
    {
        return $this->makeCall($this->onExecute, $params);
    }

    public function callRespond($params = null)
    {
        return $this->makeCall($this->onRespond, $params);
    }

    public function callTerminate($params = null)
    {
        return $this->makeCall($this->onTerminate, $params);
    }

    public function makeCall($callable, $params = null) {
        if (is_callable($callable)) {
            $callable($this, $params);
            return true;
        }

        return false;
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

            App::resolve('Cli', [array_slice($argv, 1), $this]);

            exit();
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
        $this->callLoad($files) || parent::load($files);

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
        $this->callExecute($uri) || parent::execute($uri);

        return $this;
    }

    /**
     * Dispatch respond event
     *
     * @return ApplicationInterface
     */
    public function respond(): ApplicationInterface
    {
        $this->callRespond() || parent::respond();

        return $this;
    }

    /**
     * Dispatch terminate event
     *
     * @return ApplicationInterface
     */
    public function terminate(): ApplicationInterface
    {
        $this->callTerminate() || parent::terminate();

        return $this;
    }

}