<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node;
use Lemon\Templating\Juice\EscapingContext;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class Attribute implements Node
{
    public function __construct( 
        public readonly string $name, 
        public readonly Position $position,
        public readonly ?NodeList $value = null,
    ) {

    }

    public function generate(SematicContext $context, Generators $generators): string 
    {
        if (!$this->value) {
            return $this->name;
        }

        $value = '';
        foreach ($this->value->nodes() as $node) {
            // todo escaping
            $value .= $node->generate(new SematicContext(EscapingContext::Attribute));
        }
        return "{$this->name}=\"{$value}\"";
    }
}
