<?php

namespace Lemon\Tests\Kernel\Resources\Units;

use Lemon\Tests\Kernel\Resources\IFoo;

class Baz
{
    public function __construct(IFoo $foo)
    {
        unset($foo);
    }
}
