<?php

namespace Maduser\Minimal\Framework;

/**
 * Class Nullinator
 *
 * @package Maduser\Minimal\Framework
 */
class Nullinator
{
    /**
     * @param $name
     * @param $value
     *
     * @return Nullinator
     */
    public function __set($name, $value): Nullinator
    {
        return $this;
    }

    /**
     * @param $name
     *
     * @return null
     */
    public function __get($name)
    {
        return null;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return Nullinator
     */
    public function __call($name, $arguments): Nullinator
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }
}