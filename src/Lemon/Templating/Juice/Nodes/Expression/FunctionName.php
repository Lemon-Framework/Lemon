<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Generators;
use Lemon\Templating\Juice\Position;
use Lemon\Templating\Juice\SematicContext;

// would translate to 'function'
class FunctionName implements Expression
{
    public function __construct( 
        public readonly string $name, 
        public readonly Position $position,
    ) {

    }

    public function generate(SematicContext $context, Generators $generators): string
    {
        return "'{$this->name}'";
    }
}
