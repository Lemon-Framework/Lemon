<?php

declare(strict_types=1);

namespace Lemon\Contracts\Events;

interface Dispatcher
{
    /**
     * Registers event handling function.
     */
    public function on(string $name, callable $action): static;

    /**
     * Calls all event handling functions with given arguments.
     */
    public function fire(string $name, mixed ...$args): static;

    /**
     * Returns all events.
     */
    public function events(): array;
}
