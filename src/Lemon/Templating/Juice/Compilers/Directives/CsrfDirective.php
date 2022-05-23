<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

final class CsrfDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        if ('' !== $token->content[1]) {
            throw new CompilerException('Directive csrf takes 0 arguments', $token->line);
        }

        return '<input type="hidden" name="CSRF_TOKEN" value="<?php echo \Lemon\Csrf::getToken() ?>">';
    }
}
