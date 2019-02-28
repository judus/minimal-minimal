<?php namespace Maduser\Minimal\Framework\Providers;

use App\MyCommandGroupA;
use App\MyCommandGroupB;
use Maduser\Minimal\Cli\Cli;
use Maduser\Minimal\Cli\Console;
use Maduser\Minimal\Cli\ConsoleInterface;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Commands;

/**
 * Class Provider
 *
 * @package Maduser\Minimal\Providers
 */
class CliProvider extends AbstractProvider
{
    public function bindings(): array
    {
        return [
            ConsoleInterface::class => Console::class
        ];
    }

    public function providers(): array
    {
        return [
          'Commands' => \Maduser\Minimal\Cli\Commands::class
        ];
    }
}