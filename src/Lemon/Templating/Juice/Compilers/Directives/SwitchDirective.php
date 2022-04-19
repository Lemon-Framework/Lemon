<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Templating\Juice\Exceptions\CompilerException;

final class SwitchDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        if ('' === $content) {
            throw new CompilerException('Directive switch expects arguments'); // TODO
        }

        return 'switch ('.$content.'):';
    }

    public function hasClosing(): bool
    {
        return true;
    }
}
