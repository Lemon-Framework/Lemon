<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Event Zest
 * Provides static layer over the Lemon Event Dispatcher.
 *
 * @method static static on(string $name, callable $action) Registers event handling function
 * @method static static fire(string $name, mixed ...$args) Calls all event handling functions with given arguments
 * @method static array  events()                           Returns all events
 *
 * @see \Lemon\Events\Dispatcher
 */
class Event extends Zest
{
    public static function unit(): string
    {
        return 'events';
    }
}
