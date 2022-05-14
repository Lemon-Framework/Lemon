<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice\Compilers\Directives\Layout;

use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LayoutTest extends TestCase
{
    public function render(string $file)
    {
        include $file;
    }

    public function testRendering()
    {
        ob_start();
        $this->render(__DIR__.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'foo.php');
        $result = ob_get_clean();
        $this->assertSame(<<<'HTML'


        <h1>foo</h1>

        <p>bar</p>

        HTML, $result);
    }
}
