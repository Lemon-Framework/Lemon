<?php

declare(strict_types=1);

namespace Lemon;

use Exception;
use Lemon\Kernel\Lifecycle;

abstract class Zest
{
    protected static Lifecycle $lifecycle;

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getAccessor();

        return $instance->{$name}(...$arguments);
    }

    public static function init(Lifecycle $lifecycle): void
    {
        self::$lifecycle = $lifecycle;
    }

    public static function getAccessor()
    {
        $unit = static::unit();

        return self::$lifecycle->{$unit};
    }

    abstract public static function unit(): string;
}
