<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Collections\Collection;
use Maduser\Minimal\Framework\Facades\App;
/**
 * Class CollectionProvider
 *
 * @package Maduser\Minimal\Providers
 */
class CollectionProvider extends AbstractProvider
{
    /**
     * @return Collection
     */
    public function resolve(array $params = null)
    {
        return App::make(Collection::class, $params);
    }
}