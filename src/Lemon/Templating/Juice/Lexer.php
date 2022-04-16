<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Types\Arr;
use Lemon\Templating\Juice\Exceptions\LexerException;

final class Lexer
{
    public function __construct(
        private Syntax $syntax,
    ) {
    }

    public function lex(string $template): array
    {
        $lex = preg_split($this->syntax->regex, $template, -1, PREG_SPLIT_DELIM_CAPTURE);

        return $this->tokenize($lex);
    }

    private function tokenize(array $lex): array
    {
        // TODO refactor
        $result = [];
        $lex = Arr::from($lex);
        foreach ($lex as $word) {
            if (preg_match("/^{$this->syntax->tag}$/", $word, $matches)) {
                $lex->next();
                $size = count($matches);
                $kind = Token::TAG;
                switch ($size) {
                    case 2:
                        if (preg_match("/^{$this->syntax->end}$/", $matches[1], $matches)) {
                            $kind = Token::TAG_END;
                        }
                        $content = $matches[1];
                        break;
                    case 3:
                        $content = [$matches[1], $matches[2]];
                        $lex->next();
                        break;
                    default:
                        throw new LexerException('Regex for tag should have 2 or 3 matches, '.$size.' given');
                }
                $result[] = new Token($kind, $content);
            } elseif (preg_match("/^{$this->syntax->echo}$/", $word, $matches)) {
                $result[] = new Token(Token::OUTPUT, $matches[1]);
                $lex->next();
            } elseif (preg_match("/^{$this->syntax->unescaped}$/", $word, $matches)) {
                $result[] = new Token(Token::UNESCAPED, $matches[1]);
                $lex->next();
            } elseif (preg_match("/^{$this->syntax->comment}$/", $word)) {
                // Do nothing
            } elseif ($word) {
                $result[] = new Token(Token::TEXT, $word);
            }
        }

        return $result;
    }
}
