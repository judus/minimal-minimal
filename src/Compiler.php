<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Event\Subscriber;

class Compiler extends Subscriber
{
    protected $events = [
        'minimal.loaded.bindings' => 'addBindings',
        'minimal.loaded.config' => 'addConfig',
        'minimal.loaded.modules' => 'addModules',
        'minimal.loaded.providers' => 'addProviders',
        'minimal.loaded.routes' => 'addRoutes',
        'minimal.loaded.subscribers' => 'addSubscribers',
        'minimal.terminate' => 'compileConfigs'
    ];

    protected $loadedFiles = [];

    public function add(string $type, string $file, array $array = [])
    {
        if (!empty($file) && count($array) > 0) {

            isset($this->loadedFiles[$type]) || $this->loadedFiles[$type] = [];

            $this->loadedFiles[$type][] = [
                'file' => $file,
                'contents' => $array
            ];
        }
    }

    public function addBindings(string $file = null, array $array = null)
    {
        $this->add('bindings', $file, $array);
    }

    public function addConfig(string $file = null, array $array = null)
    {
        $this->add('config', $file, $array);
    }

    public function addModules(string $file = null, array $array = null)
    {
        $this->add('modules', $file, $array);
    }

    public function addProviders(string $file = null, array $array = null)
    {
        $this->add('providers', $file, $array);
    }

    public function addRoutes(string $file = null)
    {
        //$this->add('routes', $file);
    }

    public function addSubscribers(string $file = null, array $array = null)
    {
        $this->add('subscribers', $file, $array);
    }

    public function compileConfigs()
    {
        $configs = [
            'config' => Config::items(),
            'bindings' => $this->getMerged('bindings'),
            'providers' => $this->getMerged('providers'),
            'subscribers' => $this->getMerged('subscribers'),
            'modules' => $this->getMerged('modules')
        ];

        $this->toFile($configs);
    }

    public function toFile($array)
    {
        $path = rtrim(Config::paths('system'), '/') . '/' .
            rtrim(Config::storage('app'), '/') . '/' .
            'compiled-configs.json';

        if (! is_file($path)) {
            $content = json_encode($array, JSON_PRETTY_PRINT);
            file_put_contents($path, $content, LOCK_EX);
        }
    }

    public function getMerged(string $key)
    {
        $bindings = [];

        if (isset($this->loadedFiles[$key])) {
            foreach ($this->loadedFiles[$key] as $item) {
                $bindings = array_merge($bindings, $item['contents']);
            }
        }

        return $bindings;
    }
}