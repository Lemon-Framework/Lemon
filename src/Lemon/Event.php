<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Event Zest
 * Provides static layer over the Lemon Event Dispatcher.
 *

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
