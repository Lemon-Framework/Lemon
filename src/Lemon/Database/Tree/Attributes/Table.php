<?php

declare(strict_types=1);

namespace Lemon\Database\Tree\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Table
{
    public function __construct(
        public readonly string $table
    ) {
        
    }
}
