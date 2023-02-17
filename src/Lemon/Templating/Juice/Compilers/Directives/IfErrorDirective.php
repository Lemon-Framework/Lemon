<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Token;

final class IfErrorDirective implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        return '<?php if (\Lemon\Validator::hasError()): ?>';
    }

    public function compileClosing(): string
    {
        return '<?php endif ?>';
    }
}
