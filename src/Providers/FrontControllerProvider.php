<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Controllers\FrontController;
use Maduser\Minimal\Framework\Facades\IOC;

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
        return IOC::make(FrontController::class);
    }
}