<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Token;

class BlockDirective implements Directive
{

    public function compileOpenning(Token $token, array $stack): string
    {
        $tokens = token_get_all('<?php '.$token->content[1]); // TODO better manipulation
        if (count($tokens) != 2) {
            throw new CompilerException('Directive block takes exactly 1 argument', $token->line);
        }
        if ($tokens[1][0] != T_CONSTANT_ENCAPSED_STRING) {
            throw new CompilerException('Argument 1 of directive block has to be string', $token->line);
        }

        return '$_layout->block(\''.substr($tokens[1][1], 1, -1).'\'';
    }

    public function compileEnding()
    {
        return '$_layout->endBlock()';
    }
}
