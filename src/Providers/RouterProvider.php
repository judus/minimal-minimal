<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Routing\Route;
use Maduser\Minimal\Routing\Router;

/**
 * Class RouterProvider
 *
 * @package Maduser\Minimal\Providers
 */
class RouterProvider extends AbstractProvider
{
    public function providers(): array
    {
        return [
            'Route' => RouteProvider::class
        ];
    }

    /**
     * @return mixed
     */
    public function resolve()
    {
        return $this->singleton('Router', App::make(Router::class));
    }
}