<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Support\Types\Str;
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

        return '<?= '.$method.'('.$this->resolvePipes($content).') ?>';
    }

    public function compileUnescaped(string $content): string
    {
        return '<?= '.$this->resolvePipes($content).' ?>';
    }

    private function resolvePipes(string $content): string
    {
        $parts = Str::split($content, '|>');
        if ($parts->lenght() < 2) {
            return $content;
        }

        $result = trim($parts[0]);

        foreach ($parts['1..'] as $part) {
            $part = trim($part);
            $result = "\$_env->{$part}({$result})"; 
        }

        return $result;
    }
}
