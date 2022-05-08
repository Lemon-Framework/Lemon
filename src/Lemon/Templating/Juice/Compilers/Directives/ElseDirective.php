<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

final class ElseDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        if ('if' !== Arr::last($stack)) {
            throw new CompilerException('Unexpected else', $token->line);
        }

        if ('' !== $token->content[1]) {
            throw new CompilerException('Directive else takes 0 arguments', $token->line);
        }

        return 'else:';
    }
}
