<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Exceptions\CompilerException;

final class ElseIfDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        if ('if' !== Arr::last($stack)) {
            throw new CompilerException('Unexpected elseif'); // TODO
        }

        if ('' === $content) {
            throw new CompilerException('Directive elseif takes arguments'); // TODO
        }

        return 'elseif ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return false;
    }
}
