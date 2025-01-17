<?php

declare(strict_types=1);

namespace Lemon\Contracts\Templating\Juice;

use Lemon\Templating\Juice\Token\Token;

use Lemon\Templating\Juice\Context;

interface Lexer
{
    /**
     * Returns next token in the token stream 
     * Inspired by works of Oliver Torr
     *
     * @param Context $context Context in which is the next token hapenning 
     *                         -- can change perception of the token depending
     *                         on the place in the code
     * @return Token Next token 
     */
    public function next(Context $context): ?Token;

    public function current(): Token;

}
