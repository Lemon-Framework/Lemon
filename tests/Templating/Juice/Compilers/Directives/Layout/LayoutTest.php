<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Templating\Exceptions\TemplateException;
use Lemon\Templating\Juice\Compilers\Directives\Layout\Layout;
use Lemon\Templating\Template;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class LayoutTest extends TestCase
{
    public function testRendering()
    {
        $data = ['foo' => '10'];
        $path = __DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'foo.php';
        $result = new Template($path, $path, $data);
        $this->assertSame(<<<'HTML'


        <h1>10</h1>

        <p>bar</p>

        HTML, $result->render());

        $this->assertThrowable(function () {
            $l = new FakeLayout('foo');
            $l->endBlock();
        }, TemplateException::class);

        $this->assertThrowable(function () {
            $l = new FakeLayout('foo');
            $l->startBlock('nevim');
            $l->startBlock('neco');
        }, TemplateException::class);

        ob_get_clean();

        $this->assertThrowable(function () {
            $l = new FakeLayout('foo');
            $l->yield('parek');
        }, TemplateException::class);
    }
}

class FakeLayout extends Layout
{
    public function __destruct()
    {
    }
}
