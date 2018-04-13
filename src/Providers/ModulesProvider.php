<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Framework\Facades\Config;
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
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function resolve()
    {
        return $this->singleton('Modules', new Modules(
            IOC::resolve(Collection::class),
            Config::item('paths.modules'),
            Config::item('paths.system')
        ));
    }
}