<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating;

use Lemon\Templating\Exceptions\TemplateException;
use Lemon\Templating\Template;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class TemplateTest extends TestCase
{
    public function testRendering()
    {
        $template = new Template(__DIR__.DIRECTORY_SEPARATOR.'foo.juice', __DIR__.DIRECTORY_SEPARATOR.'foo.php', ['foo' => 'bar']);
        $this->assertSame('bar', $template->render());

        $template = new Template(__DIR__.DIRECTORY_SEPARATOR.'bar.juice', __DIR__.DIRECTORY_SEPARATOR.'bar.php', []);
        $this->expectException(TemplateException::class);
        $template->render();
    }
}
