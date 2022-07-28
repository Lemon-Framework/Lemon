<?php

declare(strict_types=1);

namespace Lemon;

use Lemon\Kernel\Lifecycle;

abstract class Zest
{
    protected static Lifecycle $lifecycle;

    private function __construct()
    {
    }

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

        return self::$lifecycle->get($unit);
    }

    abstract public static function unit(): string;
}
