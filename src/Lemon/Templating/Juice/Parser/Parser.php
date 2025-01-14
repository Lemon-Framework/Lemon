<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Parser;

use Lemon\Templating\Juice\Lexer;

class Parser
{
    public function __construct( 
        public readonly Lexer $lexer,
    ) {

    }

}
