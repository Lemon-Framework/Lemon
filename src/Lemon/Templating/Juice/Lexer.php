<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Generator;
use Lemon\Templating\Juice\Syntax;
use Lemon\Templating\Juice\Token\Token;
use Lemon\Templating\Juice\Token\TokenKind;

/**
 * Lexer stream
 */
class Lexer
{
    private int $line = 1;
    private int $pos = 1; 
    private array $matches = [];
    private int $index = 0;

    /**
     * Creates new lexer stream for given input
     */
    public function __construct(
        public readonly Syntax $syntax,
        public readonly string $content,
    ) {
         preg_match_all(
            $this->syntax->re, 
            $this->content,
            $this->matches, 
            PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER
        );

    }

    /**
     * Converts given regex slug into token kind
     */
    public function getKind(string $re_slug): TokenKind 
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
    public function next(Context $context): Token 
    {
        $token = $this->matches[$this->index];
        $this->index++;
        $token = array_filter($token, fn ($item) => null !== $item);
        $keys = array_keys($token);
        if ($keys[1] == 'NewLine') {
            $this->line++;
            $this->pos = 0;
            $keys[1] = 'Html_Space';
        }

        if ($keys[1] === 'Html_Space' && $context === Context::Juice) {
            $this->pos += strlen($token[0]);
            return $this->next($context);
        }

        $result = (new Token($this->getKind($keys[1]), $this->line, $this->pos, $token[array_key_last($token)]))
                ->resolveKind($this->syntax, $context)
        ;
        $this->pos += strlen($token[0]);

        return $result;
    }
}
