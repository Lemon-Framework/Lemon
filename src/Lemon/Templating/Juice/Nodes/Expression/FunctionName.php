<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice\Nodes\Expression;

use Lemon\Contracts\Templating\Juice\Expression;
use Lemon\Templating\Juice\Position;

// would translate to 'function'
class FunctionName implements Expression
{
    public function __construct( 
        public readonly string $name, 
        public readonly Position $position,
    ) {

    }
}
