<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

final class IfDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        return 'if ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return true;
    }
}
