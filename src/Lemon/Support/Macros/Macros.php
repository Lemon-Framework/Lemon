<?php

declare(strict_types=1);

namespace Lemon\Support\Macros;

use Exception;

trait Macros
{
    /**
     * Contains all macros.
     *
     * @param array<string, callable>
     */
    private array $macros = [];

    public function __call($name, $arguments)
    {
        if (!method_exists($this, $name)) {
            throw new Exception('Call to undefined function '.$name);
        }

        return $this->macros[$name](...$arguments);
    }

    public function macro(string $name, callable $action)
    {
        $this->macros[$name] = $action;
    }
}
