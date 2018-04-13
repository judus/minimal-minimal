<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Collections\Collection;
use Maduser\Minimal\Modules\Modules;

/**
 * Class FactoryProvider
 *
 * @package Maduser\Minimal\Providers
 */
class ModulesProvider extends AbstractProvider
{
    /**
     * @return mixed
     */
    public function resolve()
    {
        return $this->singleton('Modules', new Modules(
            IOC::resolve(Collection::class)
        ));
    }
}