<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node as NodeContract;
use Lemon\Templating\Juice\Nodes\NodeList;

class Node implements NodeContract
{
    public function __construct( 
        public readonly string $name,
        public readonly ?NodeList $attributes = null,
        public readonly ?NodeList $body = null,
    ) {

    }
}
