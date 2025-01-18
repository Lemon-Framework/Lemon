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
    private Context $context;
    private Token $current;

    /**
     * Creates new lexer stream for given input
     */
    public function __construct(
        public readonly Syntax $syntax,
        private string $content,
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
     * Returns next token in the token stream based on current context 
     *
     * todo escaping
     *
     * @return Token Next token 
     */
    public function next(): ?Token 
    {
        if (!preg_match($this->syntax->getRe($this->context), $this->content, $matches)) {
            return null;
        }

        $token = array_filter($matches, fn ($item) => null != $item);
        $keys = array_keys($token);

        if ($keys[1] == 'NewLine') {
            $this->line++;
            $this->pos = 0;
            $keys[1] = 'Html_Space';
        }


        if ($keys[1] === 'Html_Space' && $this->context !== Context::Html) {
            $this->pos += strlen($token[0]);
            $this->content = substr($this->content, strlen($token[0]));
            return $this->next();
        }

        $result = (new Token($this->getKind($keys[1]), $this->line, $this->pos, $token[array_key_last($token)]));
        $this->pos += strlen($token[0]);
        $this->content = substr($this->content, strlen($token[0]));
        $this->current = $result;

        return $result;
        
    }

    /**
     * Changes current way of lexing
     * Inspired by works of Oliver Torr and John Berger
     *
     * @param Context $context Context in which is the next token hapenning 
     *                         -- can change perception of the token depending
     *                         on the place in the code
     */
    public function changeContext(Context $context): self
    {
        $this->context = $context; 

        return $this;
    }

    /**
     * Returns last lexed token
     */
    public function current(): Token
    {
        return $this->current
        ;
    }
}
