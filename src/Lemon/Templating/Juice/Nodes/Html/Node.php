<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node as NodeContract;

class Node implements NodeContract
{
    public function __construct( 
        public readonly string $name,
        /**
         * @param array<Attribute> $attributes
         * @param array<NodeContract> $nodes
         */
        public readonly array $attributes,
        public readonly array $body,
    ) {

    }
}
