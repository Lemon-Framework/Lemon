<?php

namespace Lemon\Kernel;

abstract class Unit
{
    public readonly Lifecycle $lifecycle;

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
    }
}
