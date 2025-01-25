<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

class Position
{
    public function __construct( 
        public readonly int $line, 
        public readonly int $pos,
    ) {

    }
}
