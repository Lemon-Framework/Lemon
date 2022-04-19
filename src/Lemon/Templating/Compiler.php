<?php

declare(strict_types=1);

namespace Lemon\Templating;

interface Compiler
{
    /**
     * Compiles template into php code.
     */
    public function compile(string $template): string;
}
