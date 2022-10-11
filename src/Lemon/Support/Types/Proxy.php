<?php

declare(strict_types=1);

namespace Lemon\Support\Types;

class Proxy
{
    private array $before;

    private array $after;

    public function __construct(
        public readonly object $object
    ) {

    }

    public function __call($name, $arguments)
    {
        foreach ($this->before as $action) {
            $action();
        }

        $result = $this->object->$name(...$arguments);

        foreach ($this->after as $action) {
            $action($result);
        }

        return $result;
    }

    public function __get($name)
    {
        return $this->object->$name;
    }

    public function after(callable $action): static
    {
        $this->after[] = $action;
        return $this;
    }

    public function before(callable $action): static
    {
        $this->before[] = $action;
        return $this;
    }
}
