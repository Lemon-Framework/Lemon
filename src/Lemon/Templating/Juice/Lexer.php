<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Lexer;

use Generator;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token;
use Lemon\Templating\Juice\TokenKind;

class Lexer
{
    private int $line = 1;

    private int $pos = 0;

    public function __construct(
        private string $code,
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
        
        foreach ($matches as $token) {
            $token = array_filter($token, fn ($item) => null !== $item);
            $keys = array_keys($token);

            yield new Token(TokenKind::{$keys[1]}, 0, 0, $token[1]);
        }       
    }
}
