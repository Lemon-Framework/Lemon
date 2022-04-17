<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

final class ForDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        // TODO $iterator, syntax check
        return 'for ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return true;
    }
}
