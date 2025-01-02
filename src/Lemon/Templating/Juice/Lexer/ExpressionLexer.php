<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Lexer;

use Generator;
use Lemon\Templating\Juice\Operators;
use Lemon\Templating\Juice\Token;
use Lemon\Templating\Juice\TokenKind;

class ExpressionLexer
{
    public readonly array $tokens = [
        ['OpenningBracket', '('],
        ['ClosingBracket', ')'],
        ['OpenningSquareBracket', '['],
        ['ClosingSquareBracket', ']'],
        ['DoubleArrow', '=>'],
        ['QuestionMark', '?'],
        ['Color', ':'],
        ['Comma', ','],
        ['Fn', 'fn'],
        ['String', '"([^"]+)"|\'([^\']+)\''],
        ['Number', '(-?\d+(\.\d+)?)'],
        ['Variable', '\$([a-zA-Z][a-zA-Z0-9]+)'],
        ['Name', '[a-zA-Z][a-zA-Z0-9]+'],
    ];

    public readonly string $re; 
    
    public function __construct(
        public readonly Operators $operators],
    ) {
        $this->tokens[] = ['BinaryOperator', $operators->buildBinaryRe()];
        $this->tokens[] = ['UnaryOperator', $operators->buildUnaryRe()];
        $this->re = $this->makeRe();
    }

    public function lex(string $content): Generator
    {
         preg_match_all(
            $this->re, 
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

    private function makeRe(): string
    {
        $result = '~';

        return $result.'xsA';
    }
}
