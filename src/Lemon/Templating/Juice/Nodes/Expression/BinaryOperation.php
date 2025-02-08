<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

class BinaryOperation implements Expression
{
    public function __construct(
        public readonly Expression $left,
        public readonly String $op, 
        public readonly Expression $right,
        public readonly Position $position,
    ) {

    }
}
