<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel;

use Lemon\Kernel\Application;
use Lemon\Tests\Kernel\Resources\Units\Bar;
use Lemon\Tests\Kernel\Resources\Units\Foo;
use Lemon\Tests\Kernel\Resources\Zests\Bar as ZestsBar;
use Lemon\Tests\TestCase;
use Lemon\Zest;

/**
 * @internal
 * @coversNothing
 */
class ZestTest extends TestCase
{
    public function testZest()
    {
        $lc = new Application(__DIR__);
        $lc->add(Bar::class);
        $lc->add(Foo::class);
        Zest::init($lc);

        ZestsBar::add('foo');

        $this->assertSame(['foo'], $lc->get(Bar::class)->all());
    }
}
