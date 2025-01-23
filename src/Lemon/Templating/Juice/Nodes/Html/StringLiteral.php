<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node;

class StringLiteral implements Node
{
    public function __construct(
        public readonly string $content,
    ) {

    }
}
