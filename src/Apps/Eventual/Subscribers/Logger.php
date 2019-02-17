<?php namespace Maduser\Minimal\Framework\Apps\Eventual\Subscribers;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Contracts\FactoryInterface;
use Maduser\Minimal\Framework\Facades\Log;
use Maduser\Minimal\Framework\Facades\Request;
use Maduser\Minimal\Modules\Registry;
use Maduser\Minimal\Routing\Route;
use Maduser\Minimal\Routing\Router;
use Maduser\Minimal\Framework\Facades\App;

class Logger extends Subscriber
{
    protected $events = [
        'event.dispatch' => 'onEventDispatch',
        'minimal.loaded.bindings' => 'onAppLoadedBindings',
        'minimal.loaded.providers' => 'onAppLoadedProviders',
        'minimal.loaded.minimal' => 'onAppLoadedMinimal',
        'minimal.loaded.config' => 'onAppLoadedConfig',
        'minimal.loaded.subscribers' => 'onAppLoadedSubscribers',
        'minimal.loaded.routes' => 'onAppLoadedRoutes',
        'minimal.loaded.modules' => 'onAppLoadedModules',
        'minimal.load.after' => 'onAppReady',
        'minimal.execute.before' => 'onAppExecute',
        'minimal.route.found' => 'onAppRouted',
        'minimal.middleware.dispatch.before' => 'onAppMiddlewaresDispatch',
        'minimal.frontController.dispatch.before' => 'onFrontControllerDispatch',
        'minimal.frontController.dispatch.after' => 'onFrontControllerDispatched',
        'minimal.middleware.dispatch.after' => 'onAppMiddlewaresDispatched',
        'minimal.execute.after' => 'onAppExecuted',
        'minimal.respond.before' => 'onAppRespond',
        'minimal.respond.after' => 'onAppResponded',
        'minimal.terminate' => 'onAppTerminate',
        'minimal.terminated' => 'onAppTerminated',
    ];

    protected $headerLogged = false;

    public function __construct()
    {
        //$this->header();
    }

    protected function onEventDispatch(string $eventName)
    {
        $this->debug('Event: ' . $eventName);
    }

    protected function onAppLoadedBindings(string $filePath, array $bindings)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppLoadedProviders(string $filePath, array $providers)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppLoadedMinimal(string $filePath, array $config)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppLoadedConfig(string $filePath, array $config)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppLoadedSubscribers(string $filePath, array $subscribers)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppLoadedRoutes(string $filePath)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppLoadedModules(string $filePath, array $modules)
    {
        $this->log('Loaded: ' . $filePath);
    }

    protected function onAppReady()
    {
        $this->log('App ready');
    }

    protected function onAppExecute()
    {
        Log::system('--------------------------------------------------------');
        $this->log('Execution starts...');
    }

    protected function onAppRouted(Route $route)
    {
        $this->log('Route found: ' . $route->getUriPattern());
    }

    protected function onAppMiddlewaresDispatch()
    {
        $this->log('Dispatching Middleware...');
    }

    protected function onFrontControllerDispatch()
    {
        $this->log('Dispatching FrontController...');
    }

    protected function onFrontControllerDispatched()
    {
        $this->log('FrontController dispatched');
    }
    
    protected function onAppMiddlewaresDispatched()
    {
        $this->log('Middleware dispatched');
    }
    
    protected function onAppExecuted()
    {
        $this->log('Execution end');
        Log::system('--------------------------------------------------------');
    }

    protected function onAppRespond()
    {
        $this->log('Sending response...');
    }

    protected function onAppResponded()
    {
        $this->log('Response sent');
    }

    protected function onAppTerminate()
    {
        $this->log('Terminating...');
    }

    protected function onAppTerminated()
    {
        $this->footer();
    }

    protected function header()
    {
        if (! $this->headerLogged) {
            Log::system('--------------------------------------------------------');
            Log::system('REQUEST FROM ' . Request::getIp());
            Log::info('URI: ' . Request::getUriString());
            Log::system('--------------------------------------------------------');
        }

        $this->headerLogged = true;
    }

    protected function footer()
    {
        Log::system('--------------------------------------------------------');
        Log::info('EXECUTION TIME: ' . $this->interval());
        Log::system('--------------------------------------------------------');
    }

    protected function log($message)
    {
        Log::system($this->interval() . ' | ' . $message);
    }

    protected function debug($message)
    {
        Log::debug($this->interval() . ' | ' . $message);
    }

    protected function interval()
    {
        $float = microtime(true) - APPSTART;
        return sprintf('%0.6f', (string)$float);
    }
}