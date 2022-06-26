<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Token;

interface Directive
{
    /**
     * Compiles openning directive.
     *
     * @throws \Lemon\Templating\Exceptions\CompilerException
     */
    public function compileOpenning(Token $token, array $stack): string;
}
