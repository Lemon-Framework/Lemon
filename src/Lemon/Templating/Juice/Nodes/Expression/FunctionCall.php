<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Nodes\NodeList;
use Lemon\Templating\Juice\SematicContext;
use Lemon\Templating\Juice\Position;

class FunctionCall implements Expression
{
    public function __construct(
        public readonly Expression $function,
        public readonly NodeList $arguments,
        public readonly Position $position,
    ) {
        
    }


    public function generate(SematicContext $context, Generators $generators): string
    {
        $name = '';
        if ($this->function instanceof FunctionName) {
            $name = $this->function->name;
        } else {
            $name = "({$this->function->generate($context, $generators)})";
        }

        return $name.'('.rtrim($this->arguments->generateWithDelim(', ', $context, $generators), ', ').')';
    }
}
