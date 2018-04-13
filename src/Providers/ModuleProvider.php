<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Modules\Module;

/**
 * Class ModuleProvider
 *
 * @package Maduser\Minimal\Providers
 */
class ModuleProvider extends AbstractProvider
{
    /**
     * @return Module
     */
    public function resolve()
    {
        return new Module(func_get_args()[0]);
    }
}