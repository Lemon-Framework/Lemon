<?php

declare(strict_types=1);

namespace Lemon;

use Lemon\Kernel\Application;

abstract class Zest
{
    protected static ?Application $application = null;

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
    public static function init(Application $application): void
    {
        self::$application = $application;
    }

    public static function getAccessor(): object
    {
        $unit = static::unit();

        return self::$application->get($unit);
    }

    abstract public static function unit(): string;
}
