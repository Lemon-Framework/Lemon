<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Templating\Juice\Exceptions\CompilerException;
use Lemon\Templating\Juice\Parser;

class OutputCompiler
{
    public function compileEcho(string $content, int $context): string
    {
        $method = '$_env->'.match ($context) {
            Parser::CONTEXT_HTML => 'escapeHtml',
            Parser::CONTEXT_ATTRIBUTE => 'escapeAttribute',
            Parser::CONTEXT_JS, Parser::CONTEXT_JS_ATTRIBUTE => 'escapeScript',
            default => throw new CompilerException('Unknown context')
        };

        return '<?= '.$method.'('.$content.') ?>';
    }

    public function compileUnescaped(string $content): string
    {
        return '<?= '.$content.' ?>';
    }
}
