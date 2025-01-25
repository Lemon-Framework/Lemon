<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Position;

class Attribute implements Node
{
    public function __construct( 
        public readonly string $name, 
        public readonly Position $position,
        public readonly ?NodeList $value = null,
    ) {

    }
}
