<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

final class Lexer
{
    public function __construct(
        private Syntax $syntax,
    )
    {    
    }

    private function tokenize(array $lex): array
    {
        $result = [];
        foreach ($lex as $word) {
            if (preg_match($this->syntax->tag, $word, $matches)) {
                $result[] = new Token(TokenKind::TAG, $matches[1]);
            } else if (preg_match($this->syntax->echo, $word, $matches)) {
                $result[] = new Token(TokenKind::OUTPUT, $matches[1]);               
            } else if (preg_match($this->syntax->unescaped, $word, $matches)) {
                $result[] = new Token(TokenKind::UNESCAPED, $matches[1]);               
            } else {
                $result[] = new Token(TokenKind::TEXT, $word);
            }
        }

        return $result;
    }

    public function lex(string $template): Stream
    {
        $lex = preg_split($this->syntax->regex, $template, -1, PREG_SPLIT_DELIM_CAPTURE);   
        
        return new Stream($this->tokenize($lex));
    }
}
