<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Kernel\Lifecycle;
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
        $config = new Config(new Lifecycle(__DIR__));
        $config->loadPart('kernel');

        $this->assertSame([
            'kernel' => [
                'mode' => 'web',
                'debug' => false,
            ],
        ], $config->data());

        $config->load('config');

        $config->loadPart('kernel');

        $this->assertSame([
            'kernel' => [
                'mode' => 'web',
                'debug' => false,
            ],
        ], $config->data());

        $config->loadPart('kernel', true);

        $this->assertSame([
            'kernel' => [
                'mode' => 'testing',
                'debug' => true,
            ],
        ], $config->data());

        $this->expectException(ConfigException::class);
        $config->loadPart('rizkochleboparek');
    }

    public function testGet()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $config->load('config');

        $this->assertSame('neco', $config->get('schnitzels.foo.bar'));
        $this->assertSame(['bar' => 'neco'], $config->get('schnitzels.foo'));
        $this->expectException(ConfigException::class);
        $config->get('schnitzels.parek');
    }

    public function testFile()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $config->load('config');

        $this->assertSame(
            Filesystem::join(__DIR__, 'config', 'something.txt'),
            $config->file('schnitzels.storage', 'txt')
        );
    }

    public function testSet()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $config->load('config');

        $config->set('schnitzels.foo.bar', 'parek');
        $this->assertSame('parek', $config->get('schnitzels.foo.bar'));
    }
}
