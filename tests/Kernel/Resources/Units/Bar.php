<?php

namespace Lemon\Tests\Kernel\Resources\Units;

class Bar
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
