<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Exceptions\CompilerException;

final class WhileDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        // TODO $iterator, syntax check
        if ('' === $content) {
            throw new CompilerException('Directive while expects arguments'); // TODO
        }

        return 'while ('.$content.'):';
    }

    public function compileClosing(): string
    {
        return 'endwhile';
    }
}
