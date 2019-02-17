<?php namespace Maduser\Minimal\Framework\Apps\Eventual\Subscribers;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;

class Responder extends Subscriber
{
    protected $events = [
        'minimal.respond' => 'respond'
    ];

    protected function respond()
    {
        Event::dispatch('minimal.respond.before');

        IOC::resolve('Response')->setContent(App::getResults())->send();

        Event::dispatch('minimal.respond.after');
    }
}