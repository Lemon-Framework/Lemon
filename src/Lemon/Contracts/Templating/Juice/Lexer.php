<?php

declare(strict_types=1);

namespace Lemon\Contracts\Templating\Juice;

use Lemon\Templating\Juice\Token\Token;

use Lemon\Templating\Juice\Context;

interface Lexer
{

    /**
     * Looks at next token without moving
     */
    public function peek(): ?Token;

    /**
     * Returns next token in the token stream based on current context 
     *
     * @return Token Next token 
     */
    public function next(): ?Token; 

    /**
     * Changes current way of lexing
     * Inspired by works of Oliver Torr and John Berger
     *
     * @param Context $context Context in which is the next token hapenning 
     *                         -- can change perception of the token depending
     *                         on the place in the code
     */
    public function changeContext(Context $context): self;

    /**
     * Returns last lexed token
     */
    public function current(): ?Token;

}
