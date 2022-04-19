<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers\Directives;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Exceptions\CompilerException;

final class CaseDirective implements Directive
{
    public function compileOpenning(string $content, array $stack): string
    {
        if ('switch' !== Arr::last($stack)) {
            throw new CompilerException('Unexpected case'); // TODO
        }

        if ('' === $content) {
            throw new CompilerException('Directive case expects arguments'); // TODO
        }

        return 'case '.$content.':';
    }
}
