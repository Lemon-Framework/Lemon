<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel\Resources\Zests;

use Lemon\Tests\Kernel\Resources\Units\Bar as UnitsBar;
use Lemon\Zest;

class Bar extends Zest
{
    public static function unit(): string
    {
        return UnitsBar::class;
    }
}
