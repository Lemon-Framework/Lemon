<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Generator;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token;
use Lemon\Templating\Juice\TokenKind;

class Lexer
{
    public function __construct(
        public readonly Syntax $syntax,
    ) {
    }

    public function lex(string $content): Generator
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

            yield new Token(TokenKind::{$keys[1]}, $line, $pos, $token[1] ?? '');
            $pos += strlen($token[0]);
        }       
    }
}
