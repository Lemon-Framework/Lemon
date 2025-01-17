<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes;

use Lemon\Contracts\Templating\Juice\Node;

class NodeList
{
    public function __construct( 
        /**
         * @param array<Node> $nodes
         */
        private array $nodes = []
    ) {

    }

    public function add(Node $node): self 
    {
        $this->nodes[] = $node;
        return $this;
    }
}
