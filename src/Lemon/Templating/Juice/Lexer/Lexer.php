<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Lexer;

use Lemon\Templating\Juice\Token;
use Lemon\Templating\Juice\TokenKind;

class Lexer
{
    public function lex(string $template): Token
    {  
    }

    public function lexHtml(int $pos, string $template): array|false
    {
        if ($template[$pos] !== '<') {
            return false; 
        }

        if (($comment = $this->lexHtmlComment($pos + 1, $template))) {
            return $comment;
        }

        $index = $pos + 1;
        $name = '';
        while (!ctype_space($char = $template[$index] ?? ' ')) {
            if (!$this->isNameChar($char)) {
                return false;
            }
        }

        if ()
    }

    public function lexHtmlComment(int $pos, string $template): array|false
    {
        if (!($comment = strtok(substr($template, 1), '!--'))) {
            return false;
        }

        $new_pos = $pos + strlen($comment) + 6;
        $content = strtok($comment, '-->');

        return [
            new Token(TokenKind::HtmlComment, 0, $pos, '', [], []),
            $new_pos
        ];
    }

    private function isNameChar(string $char): bool
    {
        return preg_match('/[a-zA-Z0-9:-_]/', $char) === 1;
    }
}
