<?php

declare(strict_types=1);

namespace Lemon\Tests\Testing;

use Lemon\Contracts\Routing\Router;
use Lemon\Contracts\Templating\Factory;
use Lemon\Http\Request;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Application;
use Lemon\Templating\Template;
use Lemon\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $app = new Application(__DIR__);
        $templates = Mockery::mock(Factory::class);
        $templates->allows()->getRawPath('foo.bar')->andReturns('foo/bar.juice');
        $app->add(get_class($templates), $templates);
        $app->alias(Factory::class, get_class($templates));

        $routing = Mockery::mock(Router::class);
        $routing->expects()->dispatch(Request::class)->andReturnUsing(function (Request $request) {
            if ('/' === $request->path) {
                return (new HtmlResponse(headers: ['Location' => 'foo']))->cookie('foo', 'bar');
            }

            return new TemplateResponse(new Template('foo/bar.juice', 'foo/bar.php', []));
        });
        $app->add(get_class($routing), $routing);
        $app->alias(Router::class, get_class($routing));
        $app->alias('routing', get_class($routing));

        return $app;
    }
}
