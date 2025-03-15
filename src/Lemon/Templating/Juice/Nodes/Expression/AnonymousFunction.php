<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

class AnonymousFunction implements Expression
{
    public function __construct( 
        public readonly NodeList $params,
        public readonly Expression $expression,
        public readonly Position $position,
    ) {

    }

    public function generate(SematicContext $context, Generators $generators): string 
    {
        return 'fn('
                    .rtrim($this->params->generateWithDelim(', ', $context, $generators), ', ')
                .') => '.$this->expression->generate($context, $generators);
    }

}
