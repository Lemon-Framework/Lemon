<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

interface Directive
{
    /**
     * Compiles openning directive.
     *
     * @throws \Lemon\Templating\Juice\Exceptions\CompilerException
     */
    public function compileOpenning(string $content, array $stack): string;
}
