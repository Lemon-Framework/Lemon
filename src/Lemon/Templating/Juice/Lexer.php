<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Generator;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Templating\Juice\Token\TokenKind;

class Lexer
{
    public function __construct(
        public readonly Syntax $syntax,
    ) {
    }

    public function lex(string $content, Context $context): Generator
    {
         preg_match_all(
            $this->syntax->re, 
            $content,
            $matches, 
            PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER
        );

        $line = 1;
        $pos = 0; 
        foreach ($matches as $token) {
            $token = array_filter($token, fn ($item) => null !== $item);
            $keys = array_keys($token);
            if ($keys[1] == 'NewLine') {
                $line++;
                $pos = 0;
                $keys[1] = 'Space';
            }

            if ($keys[1] === 'Space' && $context === Context::Juice) {
                $pos += strlen($token[0]);
                continue;
            }

            yield (new Token(TokenKind::{$keys[1]}, $line, $pos, $token[1] ?? $token[0]))
                    ->resolveKind($context)
            ;
            $pos += strlen($token[0]);
        }       
    }
}
