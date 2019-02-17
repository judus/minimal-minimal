<?php namespace Maduser\Minimal\Framework\Apps\Micro;

use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\AbstractApplication;
use Maduser\Minimal\Framework\ApplicationInterface;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\Router;
use Maduser\Minimal\Middlewares\AbstractMiddleware;
use Maduser\Minimal\Middlewares\Contracts\MiddlewareInterface;
use Maduser\Minimal\Middlewares\Middleware;
use Maduser\Minimal\Routing\Contracts\RouteInterface;

class MicroApplication extends AbstractApplication implements ApplicationInterface
{
}