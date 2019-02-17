<?php namespace Maduser\Minimal\Framework\Facades;

use Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException;
use Maduser\Minimal\Config\Contracts\ConfigInterface;
use Maduser\Minimal\Framework\Providers\ConfigProvider;

class Config extends Facade
{
    /**
     * @var
     */
    protected static $instance;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            self::$instance = IOC::resolve('Config');
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    public static function getItems(): array
    {
        return self::call();
    }

    /**
     * @param array $items
     *
     * @return ConfigInterface
     */
    public static function setItems(array $items): ConfigInterface
    {
        return self::call();
    }

    /**
     * @param array $items
     *
     * @return mixed
     */
    public static function items(array $items = [])
    {
        if (count($items) > 0) {
            return self::setItems($items);
        }

        return self::getItems();
    }

    /**
     * @param string $file
     *
     * @return mixed
     */
    public static function file(string $file)
    {
        return self::call();
    }

    /**
     * @return bool
     */
    public static function isLiteral(): bool
    {
        return self::call();
    }

    /**
     * @param bool $literal
     *
     * @return ConfigInterface
     */
    public static function setLiteral(bool $literal): ConfigInterface
    {
        return self::call();
    }

    /**
     * @param           $name
     * @param null      $value
     * @param null|bool $literal
     *
     * @return mixed
     * @throws KeyDoesNotExistException
     */
    public static function item($name, $value = null, $literal = null)
    {
        return self::call();
    }

    /**
     * @param      $name
     * @param      $array
     * @param null $parent
     *
     * @return mixed
     */
    public static function find($name, $array, $parent = null)
    {
        return self::call();
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws KeyDoesNotExistException
     */
    public static function throwKeyDoesNotExist($name)
    {
        return self::call();
    }
}