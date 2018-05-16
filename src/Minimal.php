<?php namespace Maduser\Minimal\Framework;

use function foo\func;
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

        $args = func_get_args();
        $args = count($args) > 0 ? $args[0] : null;

        return IOC::resolve('App', $args);
    }
}