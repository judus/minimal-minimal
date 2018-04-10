<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Event\Dispatcher;

class EventProvider extends AbstractProvider
{
    /**
     * @return mixed
     */
    public function resolve()
    {
        return $this->singleton('Event', new Dispatcher());
    }
}