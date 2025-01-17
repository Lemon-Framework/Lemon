<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Templating\Juice\Nodes\NodeList;

class Attribute implements Node
{
    public function __construct( 
        public readonly string $name, 
        public readonly NodeList $content,
    ) {

    }
}
