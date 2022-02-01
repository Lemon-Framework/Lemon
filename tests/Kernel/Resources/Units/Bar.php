<?php

namespace Lemon\Tests\Kernel\Resources\Units;

use Lemon\Kernel\Unit;

class Bar extends Unit
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
