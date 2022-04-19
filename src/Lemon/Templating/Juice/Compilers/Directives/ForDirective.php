<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Exceptions\CompilerException;

final class ForDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        // TODO $iterator, syntax check
        if ('' === $content) {
            throw new CompilerException('Directive for expects arguments'); // TODO
        }

        return 'for ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return true;
    }
}
