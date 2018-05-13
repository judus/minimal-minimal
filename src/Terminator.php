<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\Event;

class Terminator extends Subscriber
{
    protected $events = [
        'minimal.terminate' => 'terminate'
    ];

    protected function terminate()
    {
        Event::dispatch('minimal.terminated');
        exit();
    }
}