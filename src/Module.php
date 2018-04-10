<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Contracts\ModuleInterface;
use Maduser\Minimal\Framework\Contracts\FactoryInterface;

/**
 * Class Module
 *
 * @package Maduser\Minimal\Core
 */
class Module implements ModuleInterface
{
    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $basePath;

    /**
     * @var
     */
    private $bootFile;

    /**
     * @var
     */
    private $configFile;

    /**
     * @var
     */
    private $bindingsFile;

    /**
     * @var
     */
    private $providersFile;

    /**
     * @var
     */
    private $subscribersFile;

    /**
     * @var
     */
    private $routesFile;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBasePath(): string
    {
        return rtrim($this->basePath, '/') . '/';
    }

    /**
     * @param mixed $path
     */
    public function setBasePath(string $path)
    {
        $this->basePath = $path;
    }

    /**
     * @return string
     */
    public function getBootFile(): string
    {
        return $this->bootFile;
    }

    /**
     * @param mixed $bootFile
     */
    public function setBootFile(string $bootFile)
    {
        $this->bootFile = $bootFile;
    }

    /**
     * @return string
     */
    public function getConfigFile(): string
    {
        return $this->configFile;
    }

    /**
     * @param $path
     */
    public function setConfigFile(string $path)
    {
        $this->configFile = $path;
    }

    /**
     * @return string
     */
    public function getBindingsFile(): string
    {
        return $this->bindingsFile;
    }

    /**
     * @param $path
     */
    public function setBindingsFile(string $path)
    {
        $this->bindingsFile = $path;
    }

    /**
     * @return string
     */
    public function getProvidersFile(): string
    {
        return $this->providersFile;
    }

    /**
     * @param $path
     */
    public function setProvidersFile(string $path)
    {
        $this->providersFile = $path;
    }

    /**
     * @return string
     */
    public function getSubscribersFile(): string
    {
        return $this->subscribersFile;
    }

    /**
     * @param string $subscribersFile
     *
     * @return Module
     */
    public function setSubscribersFile(string $subscribersFile)
    {
        $this->subscribersFile = $subscribersFile;
    }

    /**
     * @return string
     */
    public function getRoutesFile(): string
    {
        return $this->routesFile;
    }

    /**
     * @param $path
     */
    public function setRoutesFile($path)
    {
        $this->routesFile = $path;
    }

    /**
     * Module constructor.
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public function load()
    {
        $this->registerConfig();
        $this->registerBindings();
        $this->registerProviders();
        $this->registerSubscribers();
        $this->registerRoutes();
    }

    /**
     *
     */
    public function registerConfig()
    {

    }

    /**
     *
     */
    public function registerBindings()
    {

    }

    /**
     *
     */
    public function registerProviders()
    {

    }

    /**
     *
     */
    public function registerSubscribers()
    {

    }

    /**
     *
     */
    public function registerRoutes()
    {

    }
}