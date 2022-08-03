<?php

declare(strict_types=1);

namespace Lemon\Tests\Terminal\IO;

use Exception;
use Lemon\Templating\Template;
use Lemon\Terminal\IO\Output;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class OutputTest extends TestCase
{
    public function testOut()
    {
        $output = new Output();
        $this->assertSame('foo', $output->out('foo'));
        $this->assertSame("foo\033[0m\033[0m", $output->out('<div>foo</div>'));
        $this->assertSame('10', $output->out(10));
        $path = __DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'foo.phtml';
        $this->assertSame("\033[33mnevim\033[33m\033[0m\033[0m", $output->out(new Template($path, $path, ['foo' => 'nevim'])));

        $this->expectException(Exception::class);
        $output->out($output);
    }
}
