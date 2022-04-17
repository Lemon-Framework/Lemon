<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

final class WhileDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        // TODO $iterator, syntax check
        return 'while ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return true;
    }
}
