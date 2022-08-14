<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Exception;
use Lemon\Kernel\Application;
use Lemon\Support\Env;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class EnvTest extends TestCase
{
    public function tearDown(): void
    {
        unlink(__DIR__.DIRECTORY_SEPARATOR.'.env');
    }

    public function getEnv(string $data): Env
    {
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'.env', $data);
        $lc = new Application(__DIR__);

        return new Env($lc);
    }

    public function testLoading()
    {
        $env = $this->getEnv("FOO=bar\n\n\n\r\nBAR=baz");
        $this->assertSame(['FOO' => 'bar', 'BAR' => 'baz'], $env->data());

        $this->assertThrowable(function () {
            $env = $this->getEnv("FOO=bar\r\nparek");
        }, Exception::class);

        $env = $this->getEnv("FOO=bar\r\nparek=\n");

        $this->assertSame(['FOO' => 'bar', 'parek' => null], $env->data());

        $env = $this->getEnv('');
        $this->assertSame([], $env->data());
    }

    public function testHas()
    {
        $env = $this->getEnv("FOO=bar\nBAR=");
        $this->assertTrue($env->has('FOO'));
        $this->assertFalse($env->has('FO'));
        $this->assertFalse($env->has('foo'));
        $this->assertFalse($env->has('BAR'));
    }

    public function testGet()
    {
        $env = $this->getEnv("FOO=bar\nBAR=");

        $this->assertSame('bar', $env->get('FOO'));
        $this->assertNull($env->get('BAR'));
        $this->assertSame('bar', $env->get('BAR', 'bar'));
    }

    public function testSet()
    {
        $env = $this->getEnv('');
        $env->set('FOO', 'bar');
        $this->assertSame(['FOO' => 'bar'], $env->data());
    }

    public function testCommit()
    {
        $env = $this->getEnv('FOO=baz');
        $env->set('FOO', 'bar');
        $env->set('BAZ', 'foo');

        unset($env);
        $this->assertStringEqualsFile(__DIR__.DIRECTORY_SEPARATOR.'.env', "FOO=bar\nBAZ=foo\n");
    }
}
