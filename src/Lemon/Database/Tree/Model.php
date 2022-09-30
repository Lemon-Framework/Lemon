<?php

declare(strict_types=1);

namespace Lemon\Database\Tree;

abstract class Model
{
    public function __call($name, $arguments)
    {
        // TODO we need case convertor
    }
}
