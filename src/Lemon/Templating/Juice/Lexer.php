<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Types\Stack;

class Lexer
{
    public function lex(string $template)
    {
        $tokens = new Stack(Token::class);
        $tokens->push(new Token());
        $curent = '';
        $index = 0;
        $pos = 0;
        $line = 1;

        while (!is_null($char = ($template[$index] ?? null))) {
            switch ($char) {
                case '<':
                    $tokens->push(
                        new Token(
                            TokenKind::HtmlTag,
                            $line,
                            $pos
                        )
                    );

                    break;

                case '>':
                default:
                    $curent .= $char;
            }
            ++$index;
            ++$pos;
        }
    }
}
