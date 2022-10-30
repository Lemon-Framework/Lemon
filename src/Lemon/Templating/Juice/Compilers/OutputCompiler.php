<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Compilers;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Parser;

/**
 * Provides output compilation.
 */
class OutputCompiler
{
    /**
     * Compiles output tag.
     */
    public function compileEcho(string $content, int $context): string
    {
        $method = '$_env->'.match ($context) {
            Parser::CONTEXT_HTML => 'escapeHtml',
            Parser::CONTEXT_ATTRIBUTE => 'escapeAttribute',
            Parser::CONTEXT_JS, Parser::CONTEXT_JS_ATTRIBUTE => 'escapeScript',
            default => throw new CompilerException('Unknown context')
        };

        return '<?php echo '.$method.'('.$this->resolvePipes($content).') ?>';
    }

    /**
     * Compiles unescaped output tag.
     */
    public function compileUnescaped(string $content): string
    {
        return '<?php echo '.$this->resolvePipes($content).' ?>';
    }

    /**
     * Resolves elixir-like pipes.
     */
    private function resolvePipes(string $content): string
    {
        // TODO tokenizer?
        $parts = explode('|>', $content);
        if (count($parts) < 2) {
            return $content;
        }

        $result = trim($parts[0]);

        foreach (array_slice($parts, 1) as $part) {
            $part = trim($part);
            $result = "\$_env->{$part}({$result})";
        }

        return $result;
    }
}
