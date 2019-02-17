<?php namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Collections\Collection;
use Maduser\Minimal\Event\Contracts\SubscriberInterface;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Event;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Event\Subscriber;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Modules\Module;
use Maduser\Minimal\Modules\Modules;
use Maduser\Minimal\Provider\Contracts\ProviderInterface;

class Compiler extends Subscriber
{
    protected $events = [
        'minimal.loaded.routes' => 'addRoutesFile',
        'minimal.terminate' => 'compileConfigs'
    ];

    protected $routesFiles = [];

    public function addRoutesFile($filePath) {
        $this->routesFiles[] = $filePath;
    }

    public function compileConfigs()
    {
        $providers = App::providers()->each(function($item) {
            return is_object($item) ? get_class($item) : $item;
        });

        /** @var Collection $events */
        $events = App::resolve('Collection', [App::resolve('Event')->events()]);
        $events = $events->each(function($event, $subscribers) {
            $actions = [];
            foreach ($subscribers as $subscriber) {
                /** @var SubscriberInterface $subscriber */
                $eventActions = $subscriber->getEventActions($event);
                foreach ($eventActions as $ea) {
                    $actions[$event][get_class($subscriber)][] = $ea;
                }
            }
            return $actions[$event];
        });

        /** @var Collection $modules */
        $modules = App::resolve('Modules')->all();
        $modules = $modules->each(function ($alias, $module) {
            /** @var Module $module */
            return $module->toArray();
        });

        $router = App::resolve('Router');

        dump($router->getRoutes());

        $routes = '';
        foreach ($this->routesFiles as $routesFile) {
            $routes .= $this->getScriptContent($routesFile);
        }

        $routes = implode("\n" , $this->uses) . $routes;

        $configs = [
            'config' => Config::items(),
            'bindings' => App::bindings()->getArray(),
            'providers' => $providers->getArray(),
            'subscribers' => $events->getArray(),
            'modules' => $modules->toArray(),
        ];

        $content = "<?php\n" . '$config = ' . var_export($configs, true) . ";\n";
        $content .= $routes;
        $content .= "\n" . 'return $config;';

        $this->toFile($content);
    }

    protected $uses;
    public function getScriptContent($script)
    {
        $content = file_get_contents($script);
        $content = ltrim($content, '<?php ');
        $lines = explode("\n", $content);
        $uses = [];

        foreach ($lines as &$line) {
            if ($this->startsWith($line, 'use ') && $this->endsWith($line, ';')) {
                $this->uses[$line] = $line;
                $line = '';
            }
        }

        $content = implode("\n", $lines);
        $content = str_replace("\n\n", "\n", $content);

        return $content;
    }


    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    protected function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    protected function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }


    public function toFile($content)
    {
        $path = rtrim(Config::paths('system'), '/') . '/' .
            rtrim(Config::storage('app'), '/') . '/' .
            'compiled.php';

        //if (! is_file($path)) {
            file_put_contents($path, $content, LOCK_EX);

            dump(require_once $path);
        //}
    }



}