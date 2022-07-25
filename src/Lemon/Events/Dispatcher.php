<?php

declare(strict_types=1);

namespace Lemon\Events;

use Lemon\Kernel\Lifecycle;

class Dispatcher
{
    private array $events = [];

    public function __construct(
        private Lifecycle $lifecycle,
    ) {
    }

    /**
     * Registers event handling function.
     */
    public function on(string $name, callable $action): static
    {
        $this->events[$name][] = $action;

        return $this;
    }

    /**
     * Calls all event handling functions with given arguments.
     */
    public function fire(string $name, mixed ...$args): static
    {
        foreach ($this->events[$name] as $event) {
            $this->lifecycle->call($event, $args);
        }

        return $this;
    }

    /**
     * Returns all events.
     */
    public function events(): array
    {
        return $this->events;
    }
}
