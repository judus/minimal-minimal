<?php

namespace Maduser\Minimal\Framework;

use Maduser\Minimal\Framework\Providers\Contracts\ProviderInterface;

interface ApplicationProviderInterface extends ProviderInterface
{
    /**
     * @return string
     */
    public function getApplicationClass();

    /**
     * @param string $applicationClass
     */
    public function setApplicationClass(string $applicationClass);

    /**
     * @return string
     */
    public function getBasePath();

    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath);
}

