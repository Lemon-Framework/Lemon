<?php

declare(strict_types=1);

namespace Lemon\Support;

use Closure;
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
        if (!isset($this->macros[$name])) {
            throw new Exception('Call to undefined function '.$name);
        }

        $macro = $this->macros[$name];
        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this); 
        }

        return $macro(...$arguments);
    }

    public function macro(string $name, callable $action)
    {
        $this->macros[$name] = $action;
    }
}
