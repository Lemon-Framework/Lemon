<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Exceptions\CompilerException;

final class IfDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        if ('' === $content) {
            throw new CompilerException('Directive if expects arguments'); // TODO
        }

        return 'if ('.$content.'):';
    }

    public function compileClosing(): string
    {
        return 'endif';
    }
}
