<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Kernel\Application;
use Lemon\Support\Filesystem;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConfigTest extends TestCase
{
    public function testLoading()
    {
        $config = new Config(new Application(__DIR__));
        $config->loadPart('cache');

        $this->assertSame([
            'cache' => [
                'storage' => 'storage.cache',
            ],
        ], $config->data());

        $config->load('config');

        $config->loadPart('cache');

        $this->assertSame([
            'cache' => [
                'storage' => 'storage.cache',
            ],
        ], $config->data());

        $config->loadPart('cache', true);

        $this->assertSame([
            'cache' => [
                'storage' => 'storage.cache',
            ],
        ], $config->data());

        $this->expectException(ConfigException::class);
        $config->loadPart('rizkochleboparek');
    }

    public function testGet()
    {
        $config = new Config(new Application(__DIR__));
        $config->load('config');

        $this->assertSame('neco', $config->get('schnitzels.foo.bar'));
        $this->assertSame(['bar' => 'neco'], $config->get('schnitzels.foo'));
        $this->assertNull($config->get('schnitzels.baz'));
        $this->expectException(ConfigException::class);
        $config->get('schnitzels.parek');
    }

    public function testFile()
    {
        $config = new Config(new Application(__DIR__));
        $config->load('config');

        $this->assertSame(
            Filesystem::join(__DIR__, 'config', 'something.txt'),
            $config->file('schnitzels.storage', 'txt')
        );
    }

    public function testSet()
    {
        $config = new Config(new Application(__DIR__));
        $config->load('config');

        $config->set('schnitzels.foo.bar', 'parek');
        $this->assertSame('parek', $config->get('schnitzels.foo.bar'));
    }
}
