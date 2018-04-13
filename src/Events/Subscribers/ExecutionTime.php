<?php namespace Maduser\Minimal\Framework\Events\Subscribers;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Contracts\FactoryInterface;
use Maduser\Minimal\Framework\Facades\Log;
use Maduser\Minimal\Framework\Facades\Request;
use Maduser\Minimal\Modules\Registry;
use Maduser\Minimal\Routing\Route;
use Maduser\Minimal\Routing\Router;
use Maduser\Minimal\Framework\Facades\App;

class ExecutionTime extends Subscriber
{
    protected $events = [
        'minimal.terminated' => 'onAppTerminated',
    ];

    protected function onAppTerminated()
    {
        Log::info('EXECUTION TIME: ' . $this->interval());
        Log::system('--------------------------------------------------------');
    }

    protected function interval()
    {
        $float = microtime(true) - APPSTART;
        return sprintf('%0.6f', (string)$float);
    }
}