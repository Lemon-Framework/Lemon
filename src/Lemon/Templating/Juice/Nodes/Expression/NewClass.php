<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;

class NewClass implements Expression
{
    public function __construct( 
        public readonly string $class,
        public readonly array $arguments,
    ) {

    }
}
