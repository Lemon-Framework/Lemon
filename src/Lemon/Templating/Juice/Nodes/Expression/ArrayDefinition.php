<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class ArrayDefinition implements Expression
{
    public function __construct(
        public readonly NodeList $content,
        public readonly Position $position,
    ) {

    }
    
    public function generate(SematicContext $context, Generators $generators): string 
    {
        return '['.rtrim($this->content->generateWithDelim(', ', $context, $generators), ',').']';
    }

}
