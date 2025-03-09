<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node as NodeContract;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class Node implements NodeContract
{
    public function __construct( 
        public readonly string $name,
        public readonly Position $position,
        public readonly ?NodeList $attributes = null,
        public readonly ?NodeList $body = null,
    ) {

    }

    public function generate(SematicContext $context): string 
    {
        return "<{$this->name}{$this->attributes->generate($context)}>{$this->body->generate($context)}</{$this->name}>";
    }
}
