<?php namespace Maduser\Minimal\Framework\Providers;

use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\Request;
use Maduser\Minimal\Log\Logger;

/**
 * Class LoggerProvider
 *
 * @package Maduser\Minimal\Framework\Providers
 */
class LoggerProvider extends AbstractProvider
{
    /**
     * @return Logger
     */
    public function resolve()
    {
        $logger = new Logger(
            Config::paths('system') . Config::storage('logs'),
            Config::log('level'),
            Config::log('benchmarks')
        );

        $logger->setLineFormat(function($level, $msg, $data) use ($logger) {
            return
                strftime('%Y-%m-%d %H:%M:%S')
                . ' | ' . $logger::LEVELS[$level]
                . ' | ' . $this->getRemoteIp()
                . ' | ' . $this->getRequestMethod()
                . ' | ' . $msg
                . PHP_EOL;
        });

        return $this->singleton('Log', $logger);
    }

    /**
     * @return string
     */
    private function getRemoteIp()
    {
        return str_pad(Request::getIp(), 12, ' ');
    }

    /**
     * @return string
     */
    private function getRequestMethod()
    {
        return str_pad(Request::getRequestMethod(), 6, ' ');
    }
}