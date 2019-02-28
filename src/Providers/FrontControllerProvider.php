<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Controllers\Dispatcher;
use Maduser\Minimal\Controllers\FrontController;
use Maduser\Minimal\Framework\Facades\App;

/**
 * Class FrontControllerProvider
 *
 * @package Maduser\Minimal\Providers
 */
class FrontControllerProvider extends AbstractProvider
{
    /**
     * @return FrontController
     */
    public function resolve()
    {
        return App::make(FrontController::class);
    }
}