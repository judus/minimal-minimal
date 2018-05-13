<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Application;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Providers\ApplicationProvider;
use Maduser\Minimal\Framework\Facades\IOC;

/**
 * Class Minimal
 *
 * @package Maduser\Minimal\Framework
 */
class Minimal implements AppInterface
{
    /**
     * Minimal constructor.
     *
     * @param array|null $params
     */
    public function __construct(array $params = null)
    {
        extract($params);

        isset($app) || $app = ApplicationProvider::class;

        IOC::addProviders(['App' => $app]);
    }

    /**
     * @return mixed
     */
    public function getApp()
    {
        return IOC::resolve('App', func_get_args());
    }
}