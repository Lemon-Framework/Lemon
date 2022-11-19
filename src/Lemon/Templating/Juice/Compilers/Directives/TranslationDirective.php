<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Token;

class TranslationDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        return '<?php echo \Lemon\Translator::text(\''.$token->content.'\') ?>';
    } 
}
