<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

class Indexing implements Expression
{
    public function __construct(
        public readonly Expression $index,
        public readonly Position $position,
    ) {

    }
}
