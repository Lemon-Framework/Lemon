<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes;

use Lemon\Contracts\Templating\Juice\Node;

class Comment implements Node
{
    public function __construct( 
        public readonly string $content,
    ) {

    }
}
