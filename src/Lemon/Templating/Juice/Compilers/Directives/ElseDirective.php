<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Exceptions\CompilerException;

final class ElseDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        if ('if' !== Arr::last($stack)) {
            throw new CompilerException('Unexpected else'); // TODO
        }

        if ('' !== $content) {
            throw new CompilerException('Directive else takes 0 arguments');
        }

        return 'else:';
    }
}
