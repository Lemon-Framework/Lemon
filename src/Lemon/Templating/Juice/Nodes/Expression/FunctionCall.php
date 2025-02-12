<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

class FunctionCall implements Expression
{
    public function __construct(
        public readonly Expression $function,
        /**
         * @param array<string|Expression> $arguments
         */
        public readonly array $arguments,
        public readonly Position $position,
    ) {

    }
}
