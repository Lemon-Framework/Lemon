<?php

namespace Lemon;

use Exception;
use Lemon\Kernel\Lifecycle;

abstract class Zest
{

    protected static Lifecycle $lifecycle;

    protected static function unit()
    {
        throw new Exception('Zest ' . get_called_class() . ' does not provide target unit');
    }

    public static function init(Lifecycle $lifecycle)
    {
        self::$lifecycle = $lifecycle;
    }

    public static function getAccessor()
    {
        $unit = static::unit();
        return self::$lifecycle->$unit;
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getAccessor();
        return $instance->$name(...$arguments);
    }
}
