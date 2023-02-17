<?php

declare(strict_types=1);

namespace Lemon\Events;

use Lemon\Contracts\Events\Dispatcher as DispatcherContract;
use Lemon\Kernel\Application;

class Dispatcher implements DispatcherContract
{
    private array $events = [];

    public function __construct(
        private Application $application,
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
     * Registers event handling function.
     * Alias for self::on().
     */
    public function when(string $name, callable $action): static
    {
        return $this->on($name, $action);
    }

    /**
     * Calls all event handling functions with given arguments.
     */
    public function fire(string $name, mixed ...$args): static
    {
        foreach ($this->events[$name] as $event) {
            $this->application->call($event, $args);
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
