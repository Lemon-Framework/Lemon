<?php

namespace Lemon\Tests\Kernel\Resources\Units;

use Lemon\Kernel\Container;

class Bar
{
    private array $array = [];

    public function __construct(Foo $foo)
    {
        unset($foo, $c);
    }

    public function add($item)
    {
        array_push($this->array, $item);
    }

    public function all()
    {
        return $this->array;
    }
}
