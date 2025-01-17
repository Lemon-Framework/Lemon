<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Contracts\Templating\Juice\Lexer as LexerContract;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Templating\Juice\Token\TokenKind;

/**
 * Lexer stream
 *
 * todo rewrite it, its three lexers that colide
 */
class Lexer implements LexerContract
{
    private int $line = 1;
    private int $pos = 1; 
    private int $index = 0;

    private Token $current;

    /**
     * Creates new lexer stream for given input
     */
    public function __construct(
        public readonly Syntax $syntax,
        public readonly string $content,
    ) {

    }

    /**
     * Converts given regex slug into token kind
     */
    private function getKind(string $re_slug): TokenKind 
    {
        [$group, $kind] = explode('_', $re_slug);

        return ("\\Lemon\\Templating\\Juice\\Token\\{$group}TokenKind")::{$kind};
    }

    /**
     * Returns next token in the token stream 
     * Inspired by works of Oliver Torr
     *
     * @param Context $context Context in which is the next token hapenning 
     *                         -- can change perception of the token depending
     *                         on the place in the code
     * @return Token Next token 
     */
    public function next(Context $context): ?Token 
    {
        preg_match($this->syntax->getRe($context), $this->content, $matches);


        return null;
    }

    public function current(): Token
    {
        return $this->current
        ;
    }
}
