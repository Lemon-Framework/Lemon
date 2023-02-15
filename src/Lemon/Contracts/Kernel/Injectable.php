<?php

declare(strict_types=1);

namespace Lemon\Contracts\Kernel;

use Lemon\Kernel\Container;

interface Injectable
{
    /**
     * Creates new instance from injection value.
     */
    public static function fromInjection(Container $container, mixed $value): self;
}
