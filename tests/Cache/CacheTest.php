<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use DateInterval;
use Lemon\Cache\Cache as LemonCache;
use Lemon\Cache\Exceptions\InvalidArgumentException;
use Lemon\Config\Config;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing cache without writing to actual files.
 */
class FakeCache extends LemonCache
{
    public function __construct(
        public int $time
    ) {
    }

    public function __destruct()
    {
    }
}

/**
 * @internal
 * @coversNothing
 */
class FakeCacheTest extends TestCase
{
    public function testSet()
    {
        $time = time();
        $cache = new FakeCache($time);
        $cache->set('foo', 'bar');
        $this->assertSame(['foo' => ['value' => 'bar', 'expires_at' => null]], $cache->data());
        $cache->set('foo', 'baz', 10);
        $this->assertSame(['foo' => ['value' => 'baz', 'expires_at' => $time + 10]], $cache->data());
        $cache->set('foo', 'klobna', new DateInterval('PT12S'));
        $this->assertSame(['foo' => ['value' => 'klobna', 'expires_at' => $time + 12]], $cache->data());
        $this->expectException(InvalidArgumentException::class);
        $cache->set('klobna', 'rizek', -5);
        $cache->set('klobna', 'rizek', 0);
    }

    public function testHas()
    {
        $cache = new FakeCache(time());
        $cache->set('klobna', 'dekel');
        $this->assertTrue($cache->has('klobna'));
        $this->assertFalse($cache->has('cukr'));
    }

    public function testDelete()
    {
        $cache = new FakeCache(time());
        $cache->set('klobna', 'dekel');
        $cache->delete('klobna');
        $this->assertEmpty($cache->data());
        $this->expectException(InvalidArgumentException::class);
        $cache->delete('parkovar');
    }

    public function testGet()
    {
        $cache = new FakeCache(time());
        $cache->set('foo', 'bar');
        $this->assertSame('bar', $cache->get('foo'));
        $this->assertSame('baz', $cache->get('bar', 'baz'));
        $this->assertNull($cache->get('klobasnik'));
        $cache->set('bar', 'baz', 10);
        $this->assertSame('baz', $cache->get('bar'));
        $cache->set('klobna', 'nevim', 1);
        $cache->time += 2;
        $this->assertNull($cache->get('klobna'));
        $this->assertFalse($cache->has('klobna'));
    }

    public function testSetMultiple()
    {
        $time = time();
        $cache = new FakeCache($time);
        $cache->setMultiple([
            'majkel' => 'blazen',
            'fid' => 'frajer',
            'neco' => 'rizky',
        ]);
        $this->assertSame([
            'majkel' => ['value' => 'blazen', 'expires_at' => null],
            'fid' => ['value' => 'frajer', 'expires_at' => null],
            'neco' => ['value' => 'rizky', 'expires_at' => null],
        ], $cache->data());

        $cache->setMultiple([
            'majkel' => 'podvodnik',
            'fid' => 'klobasnik',
            'neco' => 'rizek',
        ]);

        $this->assertSame([
            'majkel' => ['value' => 'podvodnik', 'expires_at' => null],
            'fid' => ['value' => 'klobasnik', 'expires_at' => null],
            'neco' => ['value' => 'rizek', 'expires_at' => null],
        ], $cache->data());

        $cache->setMultiple([
            'majkel' => 'klobasnik',
            'fid' => 'parek',
        ], 1);

        $this->assertSame([
            'majkel' => ['value' => 'klobasnik', 'expires_at' => $time + 1],
            'fid' => ['value' => 'parek', 'expires_at' => $time + 1],
            'neco' => ['value' => 'rizek', 'expires_at' => null],
        ], $cache->data());

        $cache->setMultiple([
            'majkel' => 'klobasnik',
            'fid' => 'parek',
        ], new DateInterval('PT2S'));

        $this->assertSame([
            'majkel' => ['value' => 'klobasnik', 'expires_at' => $time + 2],
            'fid' => ['value' => 'parek', 'expires_at' => $time + 2],
            'neco' => ['value' => 'rizek', 'expires_at' => null],
        ], $cache->data());

        $this->expectException(InvalidArgumentException::class);
        $cache->setMultiple(['parky', 'burty', 'horcice']);
        $cache->setMultiple(['parek' => 'chalec'], -5);
    }

    public function testGetMultiple()
    {
        $time = time();
        $cache = new FakeCache($time);
        $cache->setMultiple([
            'cs' => 'ok',
            'nevim' => 'neco',
            'fid' => 'cs',
        ]);

        $this->assertSame(['cs' => 'ok', 'fid' => 'cs'], $cache->getMultiple(['cs', 'fid']));
        $this->assertSame([
            'nevim' => 'neco',
            'klobna' => 'chalka',
            'ok' => 'chalka',
        ], $cache->getMultiple(['nevim', 'klobna', 'ok'], 'chalka'));

        $this->expectException(InvalidArgumentException::class);
        $cache->getMultiple([10, false, 'cs']);
    }

    public function testDeleteMutliple()
    {
        $time = time();
        $cache = new FakeCache($time);
        $cache->setMultiple([
            'cs' => 'ok',
            'nevim' => 'neco',
            'fid' => 'cs',
        ]);

        $cache->deleteMultiple(['cs', 'fid']);
        $this->assertSame(['nevim' => ['value' => 'neco', 'expires_at' => null]], $cache->data());

        $this->expectException(InvalidArgumentException::class);
        $cache->deleteMultiple([10, false, 'cs']);
        $cache->deleteMultiple(['cs', 'fid']);
    }

    public function testClear()
    {
        $cache = new FakeCache(time());
        $cache->setMultiple([
            'cs' => 'ok',
            'nevim' => 'neco',
            'fid' => 'cs',
        ]);
        $cache->clear();
        $this->assertEmpty($cache->data());
    }
}

/**
 * @internal
 * @coversNothing
 */
class CacheTest extends TestCase
{
    private LemonCache $cache;

    public function setUp(): void
    {
        $lc = new Lifecycle(__DIR__);
        $this->cache = new LemonCache($lc, new Config($lc));
    }

    public function testLoad()
    {
        $s = DIRECTORY_SEPARATOR;
        $dir = __DIR__.$s.'storage'.$s.'cache';
        $this->assertDirectoryExists($dir);
        $gitignore = $dir.$s.'.gitignore';
        $this->assertFileExists($gitignore);
        $data = $dir.$s.'data.json';
        $this->assertFileExists($data);
        $this->assertStringEqualsFile($gitignore, '*'.PHP_EOL.'!.gitignore');
        $this->assertStringEqualsFile($data, '{}');
        unset($this->cache);
    }

    public function testCommit()
    {
        $this->cache->setMultiple([
            'klobna' => 'neco',
            'hej' => 'ja fakt nevim',
        ], 10);
        unset($this->cache); // calling destructor

        $s = DIRECTORY_SEPARATOR;
        $this->assertJsonStringEqualsJsonFile(
            __DIR__.$s.'storage'.$s.'cache'.$s.'data.json',
            '{"klobna":{"value":"neco","expires_at":'.(time() + 10).'},"hej":{"value": "ja fakt nevim", "expires_at":'.(time() + 10).'}}'
        );
        Filesystem::delete(__DIR__.$s.'storage');
    }
}
