<?php

declare(strict_types=1);

namespace Lemon\Contracts\Templating;

interface Compiler
{
    /**
     * Compiles template into php code.
     */
    public function compile(string $template): string;

    /**
     * Returns compilers file extension.
     */
    public function getExtension(): string;
}
