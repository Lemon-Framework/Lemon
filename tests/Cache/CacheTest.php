<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use Lemon\Cache\Cache as LemonCache;
use Lemon\Kernel\Lifecycle;
use PHPUnit\Framework\TestCase;
/*
/**
 * Class for testing cache without writing to actual files
class FakeCache extends LemonCache
{
    public function __construct()
    {
        
    }

    public function __destruct()
    {
        
    }
}

class FakeCacheTest extends TestCase
{
    public function testSet()
    {
        $cache = new FakeCache();
        $cache->set('user', ['name' => 'majkel', 'id' => 37]);
        $this->assertSame(['user' => ['name' => 'majkel', 'id' => 37]], $cache->data());
        $cache->set('user', 'klobna');
        $this->assertSame(['user' => 'klobna'], $cache->data());
        $cache->set('frajer', 'fid');
        $this->assertSame(['user' => 'klobna', 'frajer' => 'fid'], $cache->data());
    }

    public function testGet()
    {
        $cache = new FakeCache();
        $cache->set('user', 'klobna');
        $this->assertSame('klobna', $cache->get('user'));
        $this->assertSame('rizek', $cache->get('chabr', function(FakeCache $c) {
            $c->set('chabr', 'rizek');
            return 'rizek';
        }));
        $this->assertSame(['user' => 'klobna', 'chabr' => 'rizek'], $cache->data());   
        $this->assertNull($cache->get('FIDO JE CHAD 90 % CHAD'));
    }

    public function testRemove()
    {
        $cache = new FakeCache();
        $cache->set('user', 'klobasnik');
        $cache->remove('user');
        $this->assertEmpty($cache->data());
    }

    public function testClear()
    {

    }
}
*/
