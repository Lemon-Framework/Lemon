<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Support\Regex;
use Lemon\Templating\Exceptions\SyntaxException;

/**
 * Juice lexer providing template to tokens conversion.
 */
final class Lexer
{
    public function __construct(
        private Syntax $syntax,
    ) {
    }

    /**
     * Converts template into array of tokens.
     */
    public function lex(string $template): array
    {
        $lex = preg_split($this->syntax->regex, $template, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);

        return $this->tokenize($template, $lex);
    }

    private function tokenize(string $template, array $lex): array
    {
        $result = [];
        $lex = new \ArrayIterator($lex);
        foreach ($lex as [$word, $offset]) {
            if (!$word) {
                continue;
            }

            $line = Regex::getLine($template, $offset);
            if (preg_match("/^{$this->syntax->tag}$/", $word, $matches)) {
                $lex->next();
                $size = count($matches);
                $kind = Token::TAG;

                switch ($size) {
                    case 2:
                        if (preg_match("/^{$this->syntax->end}$/", $matches[1], $end)) {
                            $kind = Token::TAG_END;
                            $content = $end[1];

                            break;
                        }
                        $content = [$matches[1], ''];

                        break;

                    case 3:
                        $content = [$matches[1], $matches[2]];
                        $lex->next();

                        break;

                    default:
                        throw new SyntaxException('Regex for tag must have 2 or 3 matches, '.$size.' found');
                }
                $result[] = new Token($kind, $content, $line);
            } elseif (preg_match("/^{$this->syntax->echo}$/", $word, $matches)) {
                $result[] = new Token(Token::OUTPUT, $matches[1], $line);
                $lex->next();
            } elseif (preg_match("/^{$this->syntax->unescaped}$/", $word, $matches)) {
                $result[] = new Token(Token::UNESCAPED, $matches[1], $line);
                $lex->next();
            } elseif (preg_match("/^{$this->syntax->comment}$/", $word)) {
                // Do nothing
            } else {
                $word = preg_replace($this->syntax->escape_regex, '$2', $word);
                $result[] = new Token(Token::TEXT, $word, $line);
            }
        }

        return $result;
    }
}
