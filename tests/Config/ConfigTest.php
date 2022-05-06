<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Kernel\Lifecycle;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ConfigTest extends TestCase
{
    public function testLoad()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $s = DIRECTORY_SEPARATOR;
        $config->load(__DIR__.$s.'config');
        $this->assertSame([
            'foo.bar' => __DIR__.$s.'config'.$s.'foo'.$s.'bar.php',
            'kernel' => __DIR__.$s.'config'.$s.'kernel.php',
            'schnitzels' => __DIR__.$s.'config'.$s.'schnitzels.php',
        ], $config->parts);

        $this->expectException(ConfigException::class);
        $config->load('config');
    }

    public function testPart()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $this->assertSame([
            'mode' => 'web',
            'debug' => false,
        ], $config->part('kernel')->data());
        $kernel = $config->part('kernel');
        $kernel->set('mode', 'terminal');
        $this->assertSame([
            'mode' => 'terminal',
            'debug' => false,
        ], $config->part('kernel')->data());

        $config->load(__DIR__.DIRECTORY_SEPARATOR.'config');
        $this->assertSame([
            'something' => 'cool',
        ], $config->part('foo.bar')->data());

        $this->expectException(ConfigException::class);
        $config->part('RIZKOPAREK');
    }
}
