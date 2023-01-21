<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Token;

final class ErrorDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        return '<?php echo \Lemon\Validator::error() ?>';
    }
}
