<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Apps\Maximal\MaximalApplicationProvider;
use Maduser\Minimal\Framework\Contracts\AppInterface;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Framework\Facades\App;

/**
 * Class Minimal
 *
 * @package Maduser\Minimal\Framework
 */
class Minimal implements AppInterface
{
    /**
     * @var string
     */
    protected $provider = MaximalApplicationProvider::class;

    /**
     * @var 
     */
    protected $app;

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     *
     * @return Minimal
     */
    public function setProvider(string $provider): Minimal
    {
        $this->provider = $provider;

        return $this;
    }
    
    /**
     * Minimal constructor.
     *
     * @param array|null $params
     */
    public function __construct(array $params = null)
    {
        extract($params);

        isset($provider) || $provider = $this->getProvider();

        IOC::addProviders(['App' => $provider]);
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