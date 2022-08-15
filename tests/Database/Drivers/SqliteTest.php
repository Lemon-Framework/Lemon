<?php

declare(strict_types=1);

namespace Lemon\Tests\Database\Drivers;

use Lemon\Config\Config;
use Lemon\Database\Drivers\Sqlite;
use Lemon\Kernel\Application;
use Lemon\Support\Env;
use Lemon\Tests\TestCase;
use Lemon\Zest;

/**
 * @internal
 * @coversNothing
 */
class SqliteTest extends TestCase
{
    public function setUp(): void
    {
        touch('database.sqlite');
    }

    public function tearDown(): void
    {
        unlink('database.sqlite');
    }

    public function testConnecting()
    {
        $this->expectNotToPerformAssertions(); // Just checking if we are able to connect

        $lc = new Application(__DIR__);
        $lc->add(Env::class);
        $lc->alias('env', Env::class);

        Zest::init($lc);

        $c = new Config($lc);

        new Sqlite($c);
    }
}
