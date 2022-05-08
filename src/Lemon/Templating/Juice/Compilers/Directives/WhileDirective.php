<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

final class WhileDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        // TODO $iterator, syntax check
        if ('' === $token->content[1]) {
            throw new CompilerException('Directive while expects arguments', $token->line);
        }

        return 'while ('.$token->content[1].'):';
    }

    public function compileClosing(): string
    {
        return 'endwhile';
    }
}
