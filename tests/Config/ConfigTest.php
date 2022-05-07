<?php

declare(strict_types=1);

namespace Lemon\Tests\Config;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Config\Part;
use Lemon\Kernel\Lifecycle;
use Lemon\Tests\TestCase;

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
        $config->part('kernel')->set('debug', true);
        $this->assertSame(true, $config->part('kernel')->get('debug'));

        $config->load(__DIR__.DIRECTORY_SEPARATOR.'config');
        $this->assertSame([
            'something' => 'cool',
        ], $config->part('foo.bar')->data());

        $this->assertSame(__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'something.txt', $config->part('schnitzels')->file('storage', 'txt'));

        $this->assertSame(['baz', 'nevim'], $config->part('schnitzels')->get('foo.bar'));

        $this->assertThrowable(function(Part $part) {
            $part->get('foo.baz');
        }, ConfigException::class, $config->part('schnitzels'));

        $this->assertThrowable(function(Part $part) {
            $part->get('baz');
        }, ConfigException::class, $config->part('schnitzels'));

        $this->assertThrowable(function(Config $config) {
            $config->part('klobna');
        }, ConfigException::class, $config);
    }
}
