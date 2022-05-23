<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

final class ElseIfDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        if ('if' !== Arr::last($stack)) {
            throw new CompilerException('Unexpected elseif', $token->line);
        }

        if ('' === $token->content[1]) {
            throw new CompilerException('Directive elseif takes arguments', $token->line);
        }

        return '<?php elseif ('.$token->content[1].'): ?>';
    }
}
