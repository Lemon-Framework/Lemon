<?php

declare(strict_types=1);

namespace Lemon\Tests\Http;

use Lemon\Config\Config;
use Lemon\Contracts\Http\Jsonable;
use Lemon\Contracts\Templating\Compiler;
use Lemon\Http\ResponseFactory;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\JsonResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Http\Responses\TextResponse;
use Lemon\Kernel\Application;
use Lemon\Templating\Factory;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ResponseFactoryTest extends TestCase
{
    public function getFactory()
    {
        $lc = new Application(__DIR__);
        $templates = new Factory(new Config($lc), new SimpleCompiler(), $lc);

        return new ResponseFactory($templates, $lc);
    }

    public function testResolving()
    {
        $factory = $this->getFactory();
        $this->assertInstanceOf(EmptyResponse::class, $factory->resolve(null));
        $this->assertInstanceOf(HtmlResponse::class, $factory->resolve('foo'));
        $this->assertInstanceOf(JsonResponse::class, $factory->resolve(['foo' => 10]));
        $this->assertInstanceOf(TextResponse::class, $factory->resolve(new TextResponse()));
        $this->assertInstanceOf(HtmlResponse::class, $factory->resolve(new HtmlResponse()));
        $this->assertInstanceOf(JsonResponse::class, $factory->resolve(new SimpleJson([1, 2, 3])));
        $this->assertInstanceOf(TemplateResponse::class, $factory->resolve(new TemplateResponse()));
    }

    public function testError()
    {
        $factory = $this->getFactory();

        $this->assertSame(<<<'HTML'
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>
                Internal Server Error</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap');
                
                * {
                    font-family: 'Roboto', sans-serif;
                }
            </style>
        </head>
        <body style="min-height: 100vh; margin: 0; align-items: center; display: flex">
            <h1 style="display: block; margin-left: 1%">
                500        <hr>
                Internal Server Error    </h1>
        </body>
        </html>

        HTML, $factory->error(500)->parseBody());

        $this->assertSame("404 rip bozo\n", $factory->error(404)->parseBody());

        $this->expectException(\InvalidArgumentException::class);
        $factory->error(515);
    }

    public function testHandling()
    {
        $lc = new Application(__DIR__);
        $lc->add(SimpleLogger::class);
        $templates = new Factory(new Config($lc), new SimpleCompiler(), $lc);
        $factory = new ResponseFactory($templates, $lc);

        $factory->handle(500, function (SimpleLogger $log) {
            $log->log('500');

            return '500';
        });

        $factory->handle(400, function (SimpleRequest $request) {
            if ('api' == $request->uri) {
                return new JsonResponse(['code' => 400], 400);
            }
        });

        $lc->add(SimpleRequest::class, new SimpleRequest('api'));
        $this->assertSame('{"code":400}', $factory->error(400)->parseBody());
        $lc->add(SimpleRequest::class, new SimpleRequest('foo'));
        $this->assertInstanceOf(TemplateResponse::class, $factory->error(400));

        $this->assertSame('500', $factory->error(500)->parseBody());
        $this->assertSame(['500'], $lc->get(SimpleLogger::class)->all());
    }
}

class SimpleLogger
{
    private array $messages = [];

    public function log(string $message): static
    {
        $this->messages[] = $message;

        return $this;
    }

    public function all(): array
    {
        return $this->messages;
    }
}

class SimpleCompiler implements Compiler
{
    public function compile(string $template): string
    {
        return $template;
    }

    public function getExtension(): string
    {
        return 'phtml';
    }
}

class SimpleJson implements Jsonable
{
    public function __construct(
        private array $json
    ) {
    }

    public function toJson(): array
    {
        return $this->json;
    }
}

class SimpleRequest
{
    public function __construct(
        public string $uri
    ) {
    }
}
