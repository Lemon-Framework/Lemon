<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives\Layout;

use Closure;

class Layout
{
    private array $blocks;

    public function __construct(
        private string $file,
    ) {
    }

    public function __destruct()
    {
        $_layout = $this;

        include $this->file;
    }

    public function block(string $name, Closure $action)
    {
        $this->blocks[$name] = $action;
    }

    public function yield(string $name)
    {
        ob_start();
        $this->blocks[$name]();
        echo trim(ob_get_clean());
    }
}
