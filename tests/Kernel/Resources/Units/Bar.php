<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel\Resources\Units;

class Bar
{
    private array $array = [];

    public function __construct(Foo $foo)
    {
        unset($foo);
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
