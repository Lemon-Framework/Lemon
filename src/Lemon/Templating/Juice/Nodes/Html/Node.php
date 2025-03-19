<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Html;

use Lemon\Contracts\Templating\Juice\Node as NodeContract;
use Lemon\Templating\Juice\Generators;
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

    public function generate(SematicContext $context, Generators $generators): string 
    {
        return 
            "<".$this->name.($this->attributes ? ' '.$this->attributes->generate($context, $generators) : '').">"
            .($this->body !== null 
                ? $this->body->generate($context, $generators)
                  ."</".$this->name.">"
                : ''
            );
    }
}
