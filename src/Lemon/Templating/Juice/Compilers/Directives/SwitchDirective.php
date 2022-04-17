<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

final class SwitchDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        return 'switch ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return true;
    }
}

