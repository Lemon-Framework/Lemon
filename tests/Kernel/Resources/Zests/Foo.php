<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel\Resources\Zests;

use Lemon\Zest;

class Foo extends Zest
{
    public static function unit(): string
    {
        return 'foo';
    }
}
