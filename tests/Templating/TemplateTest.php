<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating;

use Lemon\Templating\Template;
use Lemon\Tests\TestCase;

class TemplateTest extends TestCase
{
    public function testRendering()
    {
        $template = new Template(__DIR__.DIRECTORY_SEPARATOR.'foo.juice', __DIR__.DIRECTORY_SEPARATOR.'foo.php', ['foo' => 'bar']);
        ob_start();
        $template->render();
        $this->assertSame('bar', ob_get_clean());
    }
}
