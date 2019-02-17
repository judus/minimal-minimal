<?php namespace Maduser\Minimal\Framework\Apps\Micro;

use Maduser\Minimal\Framework\AbstractApplicationProvider;
use Maduser\Minimal\Framework\Apps\Micro\MicroApplication;

/**
 * Class ApplicationProvider
 *
 * @package Maduser\Minimal\Framework\Providers
 */
class MicroApplicationProvider extends AbstractApplicationProvider
{
    protected $applicationClass = MicroApplication::class;
}