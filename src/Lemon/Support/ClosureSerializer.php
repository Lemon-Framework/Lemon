<?php

declare(strict_types=1);

namespace Lemon\Support;

use Closure;
use Exception;
use PhpToken;
use ReflectionFunction;

class ClosureSerializer
{
    /**
     * Converts closure into string of its code.
     */
    public static function serialize(Closure $closure): string
    {
        $reflection = new ReflectionFunction($closure);
        $file = file_get_contents($reflection->getFileName());
        $code = '<?php '.implode("\n", 
            array_slice(
                explode("\n", $file),
                $reflection->getStartLine() - 1,
                ($reflection->getEndLine() - $reflection->getStartLine() + 1)
            )
        );      

        $tokens = PhpToken::tokenize($code);
        foreach ($tokens as $index => $token) {
            if ($token->is('function')) {
                return self::serializeFunction(array_slice($tokens, $index));
            } 

            if ($token->is('fn')) {
                return self::serializeArrowFunction(array_slice($tokens, $index));
            }
        }

        throw new Exception('Parse error');
    }

    /**
     * @param array<\PhpToken> $tokens
     */
    public static function serializeFunction(array $tokens): string
    {
        $braces = 0;
        $result = '';

        foreach ($tokens as $token) {
            if ($token->is(T_COMMENT)) {
                continue;
            }

            if ($token->is(T_WHITESPACE)) {
                $result .= ' ';
                continue;
            }

            $result .= $token->text;

            if ($token->is('{')) {
                $braces++;
            }

            if ($token->is('}')) {
                $braces--;
                if ($braces == 0) {
                    return $result;
                }
            }
        }

        throw new Exception('Unclosed function');
    }

    /**
     * @param array<\PhpToken> $tokens
     */
    public static function serializeArrowFunction(array $tokens): string
    {
        $braces = 0;
        $result = '';

        foreach ($tokens as $token) {
            if ($token->is(T_COMMENT)) {
                continue;
            }

            if ($token->is(T_WHITESPACE)) {
                $result .= ' ';
                continue;
            }

            if ($token->is('(')) {
                $braces++;
            }

            if ($token->is(')')) {
                if ($braces == 0) {
                    return $result;
                }
                $braces--;
            }

            if ($token->is(',')) {
                if ($braces == 0) {
                    return $result;
                }
            }

            if ($token->is(';')) {
                return $result;
            }

            $result .= $token->text;
        }

        throw new Exception('Unclosed function');
    }
}
