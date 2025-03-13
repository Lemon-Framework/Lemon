<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes;

use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\SematicContext;

class NodeList implements Node
{
    public function __construct( 
        /**
         * @param array<Node> $nodes
         */
        private array $nodes = [],
    ) {

    }

    public function add(Node $node): self 
    {
        $this->nodes[] = $node;
        return $this;
    }


    public function nodes(): array 
    {
        return $this->nodes;
    }

    public function generate(SematicContext $context, Generators $generators): string 
    {
        $result = '';
        foreach ($this->nodes() as $node) {
            $result .= ' '.$node->generate($context);
        }

        return $result;
    }
}
