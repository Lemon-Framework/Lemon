<?php

namespace Lemon\Kernel;

abstract class Unit
{
    private Lifecycle $lifecycle;

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
    }
}
