<?php namespace Maduser\Minimal\Framework\Events\Subscribers;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\Log;

class SystemLog extends Subscriber
{
    protected $events = [
        'app.ready' => 'onAppReady',
        'app.routed' => 'onAppRouted',
        'app.frontController.dispatch' => 'onFrontControllerDispatch',
        'app.frontController.dispatched' => 'onFrontControllerDispatched',
        'app.respond' => 'onAppRespond',
        'app.responded' => 'onAppResponded',
        'app.terminate' => 'onAppTerminate',
        'app.terminated' => 'onAppTerminated',
    ];

    public function onAppReady()
    {
        Log::system('------------------------------------------------');
        Log::system('NEW REQUEST');
        Log::system('------------------------------------------------');
        Log::system($this->interval() . ' - App is ready');
    }

    public function onAppRouted()
    {
        Log::system($this->interval() . ' - App is routed');
    }

    public function onFrontControllerDispatch()
    {
        Log::system($this->interval() . ' - App is about to dispatch the FrontController');
    }

    public function onFrontControllerDispatched()
    {
        Log::system($this->interval() . ' - App has dispatched the FrontController');
    }

    public function onAppRespond()
    {
        Log::system($this->interval() . ' - App is about to respond');
    }

    public function onAppResponded()
    {
        Log::system($this->interval() . ' - App has responded');
    }

    public function onAppTerminate()
    {
        Log::system($this->interval() . ' - App is about to terminate');
    }

    public function onAppTerminated()
    {
        Log::system($this->interval() . ' - App is terminating');
        Log::system('------------------------------------------------');
        Log::system('DURATION: ' . $this->interval());
        Log::system('------------------------------------------------');
    }

    protected function interval()
    {
        return microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    }
}