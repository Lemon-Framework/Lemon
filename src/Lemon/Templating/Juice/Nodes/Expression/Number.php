<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

class Number implements Expression
{
    public function __construct(
        public readonly string $content,
        public readonly Position $position,
    ) {

    }
}
