<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

class StringLiteral implements Expression
{
    public function __construct(
        /**
         * @param array<string|Expression> $content
         */
        public readonly array $content,
        public readonly Position $position,
    ) {

    }
}
