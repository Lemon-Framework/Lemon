<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

final class CaseDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        if ('switch' !== Arr::last($stack)) {
            throw new CompilerException('Unexpected case', $token->line);
        }

        if ('' === $token->content[1]) {
            throw new CompilerException('Directive case expects arguments', $token->line); // TODO
        }

        return 'case '.$token->content[1].':';
    }
}
