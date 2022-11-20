<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Token;

class TranslationDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        if ('' === $token->content[1]) {
            throw new CompilerException('Directive text expects one argument');
        }
        return '<?php echo \Lemon\Translator::text(\''.$token->content[1].'\') ?>';
    } 
}
