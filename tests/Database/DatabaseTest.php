<?php

declare(strict_types=1);

namespace Lemon\Tests\Database;

use Lemon\Config\Config;
use Lemon\Database\Database;
use Lemon\Database\Drivers\Sqlite;
use Lemon\Kernel\Application;
use Lemon\Support\Env;
use Lemon\Tests\TestCase;
use Lemon\Zest;

/**
 * @internal
 * @coversNothing
 */
class DatabaseTest extends TestCase
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
        $lc = new Application(__DIR__);
        $lc->add(Env::class);
        $lc->alias('env', Env::class);

        Zest::init($lc);

        $d = new Database(new Config($lc));

        $driver = $d->getConnection();
        $this->assertInstanceOf(Sqlite::class, $driver);

        $this->assertSame($driver, $d->getConnection());
    }

    public function testQuery()
    {
        $lc = new Application(__DIR__);
        $lc->add(Env::class);
        $lc->alias('env', Env::class);

        Zest::init($lc);

        $d = new Database(new Config($lc));

        $d->query('CREATE TABLE example (name varchar)');
        $foo = 'majkel';
        $d->query('INSERT INTO example VALUES (?)', $foo);

        $r = $d->query('SELECT * FROM example WHERE name=:name', name: $foo);

        $this->assertSame([
            [
                'name' => 'majkel',
                0 => 'majkel',
            ],
        ], $r->fetchAll());
    }
}
