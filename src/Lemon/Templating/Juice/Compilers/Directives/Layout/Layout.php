<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Support\Types\Arr;

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

    public function block(string $name)
    {
        $this->blocks[$name] = '';
        ob_start();
    }

    public function endBlock()
    {
        $this->blocks[Arr::lastKey($this->blocks)] = ob_get_clean();
    }

    public function yield(string $name)
    {
        echo trim($this->blocks[$name]);
    }
}
