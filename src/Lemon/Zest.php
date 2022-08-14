<?php

declare(strict_types=1);

namespace Lemon;

use Lemon\Kernel\Application;

abstract class Zest
{
    protected static ?Application $lifecycle = null;

    private function __construct()
    {
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getAccessor();

        return $instance->{$name}(...$arguments);
    }

    /**
     * Initializes zests.
     */
    public static function init(Application $lifecycle): void
    {
        self::$lifecycle = $lifecycle;
    }

    public static function getAccessor(): object
    {
        $unit = static::unit();

        return self::$lifecycle->get($unit);
    }

    abstract public static function unit(): string;
}
