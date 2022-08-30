<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating;

use Lemon\Config\Config;
use Lemon\Contracts\Templating\Compiler;
use Lemon\Kernel\Application;
use Lemon\Support\Filesystem as FS;
use Lemon\Support\Types\Str;
use Lemon\Templating\Environment;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Exceptions\TemplateException;
use Lemon\Templating\Factory;
use Lemon\Templating\Template;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class FactoryTest extends TestCase
{
    public function tearDown(): void
    {
        $s = DIRECTORY_SEPARATOR;
        FS::delete(__DIR__.$s.'storage'.$s.'templates');
    }

    public function testGetRawPath()
    {
        $factory = $this->getFactory();
        $s = DIRECTORY_SEPARATOR;
        $this->assertSame(__DIR__.$s.'templates'.$s.'foo'.$s.'bar.foo', $factory->getRawPath('foo.bar'));

        $this->assertFalse($factory->getRawPath('klobna'));
    }

    public function testGetCompiledPath()
    {
        $factory = $this->getFactory();
        $s = DIRECTORY_SEPARATOR;
        $this->assertSame(__DIR__.$s.'storage'.$s.'templates'.$s.'foo_bar_template.php', $factory->getCompiledPath('foo.bar.template'));
    }

    public function testCompilation()
    {
        $factory = $this->getFactory();

        $s = DIRECTORY_SEPARATOR;
        $raw_dir = __DIR__.$s.'templates';
        $compiled_dir = __DIR__.$s.'storage'.$s.'templates';

        $time = time();

        $raw = $raw_dir.$s.'test.foo';
        $compiled = $compiled_dir.$s.'test.php';

        touch($raw);

        $factory->compile($raw, $compiled);

        $this->assertDirectoryExists($compiled_dir);

        $this->assertStringEqualsFile($compiled, '1');

        $factory->compile($raw, $compiled);

        $this->assertStringEqualsFile($compiled, '1');

        touch($raw, $time + 1);

        $factory->compile($raw, $compiled);

        $this->assertStringEqualsFile($compiled, '2');
        $this->assertThrowable(function (Factory $factory, string $raw, string $compiled) {
            $factory->compile(
                $raw.DIRECTORY_SEPARATOR.'foo'.DIRECTORY_SEPARATOR.'baz.foo',
                $compiled.DIRECTORY_SEPARATOR.'foo_baz.php'
            );
        }, TemplateException::class, $factory, $raw_dir, $compiled_dir);

        FS::delete($raw);
        FS::delete($compiled);
    }

    public function testMake()
    {
        $factory = $this->getFactory();

        $s = DIRECTORY_SEPARATOR;

        $this->assertThat(new Template(
            __DIR__.$s.'templates'.$s.'foo'.$s.'bar.foo',
            __DIR__.$s.'storage'.$s.'templates'.$s.'foo_bar.php',
            ['foo' => 'bar', '_env' => new Environment(), '_factory' => $factory]
        ), $this->equalTo($factory->make('foo.bar', ['foo' => 'bar'])));
    }

    public function testExist()
    {
        $this->assertTrue($this->getFactory()->exist('foo.bar'));
        $this->assertFalse($this->getFactory()->exist('foo.ba'));
    }

    private function getFactory(): Factory
    {
        $lc = new Application(__DIR__);

        return new Factory(new Config($lc), new FooCompiler(), $lc);
    }
}

class FooCompiler implements Compiler
{
    private int $counter = 0;

    public function compile(string $template): string
    {
        if (Str::startsWith($template, 'baz')) {
            throw new CompilerException('', 1);
        }
        ++$this->counter;

        return (string) $this->counter.$template;
    }

    public function getExtension(): string
    {
        return 'foo';
    }
}
