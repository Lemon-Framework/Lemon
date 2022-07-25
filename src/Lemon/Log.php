<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Logger Zest
 * Provides static layer over the Lemon log.
 *
 * @method static void log($level, string|Stringable $message, array $context = []) {@inheritdoc}
 * @method static string interpolate(string $message, array $context)               Compiles message with given context
 *
 * @see \Lemon\Logging\Logger
 */
class Log extends Zest
{
    public static function unit(): string
    {
        return 'log';
    }
}
