<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testLoad()
    {
        $config = new Config();
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
        $config = new Config();
        $this->assertSame([
            'mode' => 'web',
            'debug' => false,
        ], $config->part('kernel')->content);
        $kernel = $config->part('kernel');
        $kernel['mode'] = 'terminal';
        $this->assertSame([
            'mode' => 'terminal',
            'debug' => false,
        ], $config->part('kernel')->content);

        $config->load(__DIR__.DIRECTORY_SEPARATOR.'config');
        $this->assertSame([
            'something' => 'cool',
        ], $config->part('foo.bar')->content);

        $this->expectException(ConfigException::class);
        $config->part('RIZKOPAREK');
    }
}
