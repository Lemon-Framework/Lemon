<?php

namespace Lemon\Tests\Kernel\Resources\Units;

use Lemon\Tests\Kernel\Resources\IFoo;

class Foo implements IFoo
{
    private array $array;

    public function add($item)
    {
        array_push($this->array, $item);
    }

    public function all()
    {
        return $this->array;
    }
}
