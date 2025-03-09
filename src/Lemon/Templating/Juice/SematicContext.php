<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

use Lemon\Templating\Juice\EscapingContext;

class SematicContext
{
    public function __construct(
        public readonly EscapingContext $escaping, 
    ) {
        
    } 
}
