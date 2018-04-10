<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Log\Logger;
use Maduser\Minimal\Framework\Facades\Config;

class LoggerProvider extends AbstractProvider
{
    /**
     * @return mixed
     */
    public function resolve()
    {
        return $this->singleton('Log',
            new Logger(path('logs'), Config::log('level'), Config::log('benchmarks'))
        );
    }
}