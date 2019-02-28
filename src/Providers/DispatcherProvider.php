<?php

namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Controllers\CliDispatcher;
use Maduser\Minimal\Controllers\Contracts\TypedDispatcherInterface;
use Maduser\Minimal\Controllers\Dispatcher;
use Maduser\Minimal\Controllers\HttpDispatcher;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Request;
use Maduser\Minimal\Framework\Providers\AbstractProvider;

class DispatcherProvider extends AbstractProvider
{
    public function bindings(): array
    {
        $dispatcher = php_sapi_name() == 'cli' || defined('STDIN') ?
            CliDispatcher::class : HttpDispatcher::class;

        return [
            TypedDispatcherInterface::class => $dispatcher
        ];
    }    /**
     * @return Dispatcher
     */
    public function resolve()
    {
        return App::make(Dispatcher::class);
    }
}
