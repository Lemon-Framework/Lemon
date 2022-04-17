<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Exceptions\CompilerException;

final class CaseDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        if (Arr::last($stack) !== 'switch') {
            throw new CompilerException('Unexpected switch'); // TODO
        }

        return 'case '.$content.':';
    }

    public function hasClosing(): bool
    {
        return false;
    }
}
