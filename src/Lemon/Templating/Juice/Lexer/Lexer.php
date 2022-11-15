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
        public readonly Syntax $syntax
    ) {
        
    }

    public function lex(): Generator
    {
        preg_match_all(
            $this->syntax->re, 
            $this->code,
            $matches, 
            PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER
        );
        
        foreach ($matches as $token) {
            $token = array_filter($token, fn ($item) => null !== $item);
            $keys = array_keys($token);

            yield from $this->{'lex'.$keys[1]}($token);
        }
    }

    public function lexHtmlStart(): Generator
    {
        yield new Token(TokenKind::HtmlTagStart, $this->line, $this->pos);
    }

    public function lexHtmlEnd(): Generator
    {
        yield new Token(TokenKind::HtmlTagEnd, $this->line, $this->pos);
    }

    public function lexHtmlClose(): Generator
    {
        yield new Token(TokenKind::HtmlCloseTag, $this->line, $this->pos);
    }


}