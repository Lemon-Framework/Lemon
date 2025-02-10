<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

class ArrayDefinition implements Expression
{
    public function __construct(
        /**
         * @param array<Expression> $content
         */
        public readonly array $content,
        public readonly Position $position,
    ) {

    }
}
