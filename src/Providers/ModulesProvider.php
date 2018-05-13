<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Framework\Facades\App;
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
        return App::singleton('Modules', function() {
            return new Modules(
                App::resolve(Collection::class),
                Config::item('paths.modules'),
                Config::item('paths.system')
            );
        });
    }
}