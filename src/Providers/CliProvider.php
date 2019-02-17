<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Cli\Cli;
use Maduser\Minimal\Framework\Facades\App;
/**
 * Class Provider
 *
 * @package Maduser\Minimal\Providers
 */
class CliProvider extends AbstractProvider
{
    /**
     *
     */
    public function init()
    {
    }

    /**
     *
     */
    public function register()
    {
    }

    /**
     *
     */
    public function resolve($args = null)
    {
        return App::make(Cli::class, $args);
    }


}