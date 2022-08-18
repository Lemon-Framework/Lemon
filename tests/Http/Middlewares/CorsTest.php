<?php

declare(strict_types=1);

namespace Lemon\Tests\Http\Middlewares;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Http\Middlewares\Cors;
use Lemon\Http\Request;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Kernel\Application;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CorsTest extends TestCase
{
    public function testAllowedOrigins()
    {
        $config = new Config(new Application(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-origins', '*');

        

        $this->assertSame(
            ['Access-Control-Allow-Origin' => '*'],
            $c->handle($config, new Request('/', '', 'GET', ['Origin' => 'tvojemama'], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.allowed-origins', 'parek');
        

        $this->assertSame(
            ['Access-Control-Allow-Origin' => 'parek'],
            $c->handle($config, new Request('parek', '', 'GET', ['Origin' => 'parek'], '', []), new HtmlResponse())->headers()
        );

        

        $this->assertSame(
            ['Access-Control-Allow-Origin' => 'parek'],
            $c->handle($config, new Request('RIZKOCHLEBOPARKOVAR', '', 'GET', ['Origin' => 'nevim'], '', []), new HtmlResponse())->headers(),
        );

        
        $config->set('http.cors.allowed-origins', ['/', 'foo', 'klobna']);

        $this->assertSame(
            ['Access-Control-Allow-Origin' => 'foo'],
            $c->handle($config, new Request('/foo', '', 'GET', ['Origin' => 'foo'], '', []), new HtmlResponse())->headers()
        );
        

        $this->assertEmpty(
            $c->handle($config, new Request('/foo', '', 'GET', ['Origin' => 'parek'], '', []), new HtmlResponse())->headers(),
        );

        
        $this->assertEmpty(
            $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers(),
        );

        $config->set('http.cors.allowed-origins', 10);
        
        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers();
    }

    public function testExposeHeaders()
    {
        $config = new Config(new Application(__DIR__));
        $c = new Cors();

        $config->set('http.cors.expose-headers', 'Parek');

        
        $this->assertSame(
            ['Access-Control-Expose-Headers' => 'Parek'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.expose-headers', ['Parek', 'Rohlik']);
        

        $this->assertSame(
            ['Access-Control-Expose-Headers' => 'Parek, Rohlik'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.expose-headers', 10);
        

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers();
    }

    public function testMaxAge()
    {
        $config = new Config(new Application(__DIR__));
        $c = new Cors();

        $config->set('http.cors.max-age', 10);
        

        $this->assertSame(
            ['Access-Control-Max-Age' => '10'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers(),
        );

        $config->set('http.cors.max-age', 'parek');
        

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers();
    }

    public function testCredentials()
    {
        $config = new Config(new Application(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-credentials', true);
        

        $this->assertSame(
            ['Access-Control-Allow-Credentials' => 'true'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.allowed-credentials', 'parek');
        

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers();
    }

    public function testAllowedMethods()
    {
        $config = new Config(new Application(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-methods', 'GET');
        

        $this->assertSame(
            ['Access-Control-Allow-Methods' => 'GET'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.allowed-methods', ['PUT', 'POST']);

        $this->assertSame(
            ['Access-Control-Allow-Methods' => 'PUT, POST'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.allowed-methods', 10);

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers();
    }

    public function testAllowedHeaders()
    {
        $config = new Config(new Application(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-headers', 'Parek');

        $this->assertSame(
            ['Access-Control-Allow-Headers' => 'Parek'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.allowed-headers', ['Parek', 'Rohlik']);

        $this->assertSame(
            ['Access-Control-Allow-Headers' => 'Parek, Rohlik'],
            $c->handle($config, new Request('/', '', 'GET', [], '', []), new HtmlResponse())->headers()
        );

        $config->set('http.cors.allowed-headers', 10);

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []), new HtmlResponse())->headers();
    }
}
