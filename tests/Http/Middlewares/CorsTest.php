<?php

declare(strict_types=1);

namespace Lemon\Tests\Http\Middlewares;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Http\Middlewares\Cors;
use Lemon\Http\Request;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Kernel\Lifecycle;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CorsTest extends TestCase
{
    public function testAllowedOrigins()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-origins', '*');

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', ['Origin' => 'tvojemama'], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Origin' => '*']))
        );

        $config->set('http.cors.allowed-origins', 'parek');

        $this->assertThat(
            $c->handle($config, new Request('parek', '', 'GET', ['Origin' => 'parek'], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Origin' => 'parek']))
        );

        $this->assertThat(
            $c->handle($config, new Request('RIZKOCHLEBOPARKOVAR', '', 'GET', ['Origin' => 'nevim'], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Origin' => 'parek']))
        );

        $config->set('http.cors.allowed-origins', ['/', 'foo', 'klobna']);

        $this->assertThat(
            $c->handle($config, new Request('/foo', '', 'GET', ['Origin' => 'foo'], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Origin' => 'foo']))
        );

        $this->assertThat(
            $c->handle($config, new Request('/foo', '', 'GET', ['Origin' => 'parek'], '', [])),
            $this->equalTo(new EmptyResponse())
        );

        $this->assertThat(
            $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse())
        );

        $config->set('http.cors.allowed-origins', 10);
        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []));
    }

    public function testExposeHeaders()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $c = new Cors();

        $config->set('http.cors.expose-headers', 'Parek');

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Expose-Headers' => 'Parek']))
        );

        $config->set('http.cors.expose-headers', ['Parek', 'Rohlik']);

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Expose-Headers' => 'Parek, Rohlik']))
        );

        $config->set('http.cors.expose-headers', 10);

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []));
    }

    public function testMaxAge()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $c = new Cors();

        $config->set('http.cors.max-age', 10);

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Max-Age' => '10']))
        );

        $config->set('http.cors.max-age', 'parek');

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []));
    }

    public function testCredentials()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-credentials', true);

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Credentials' => 'true']))
        );

        $config->set('http.cors.allowed-credentials', 'parek');

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []));
    }

    public function testAllowedMethods()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-methods', 'GET');

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Methods' => 'GET']))
        );

        $config->set('http.cors.allowed-methods', ['PUT', 'POST']);

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Methods' => 'PUT, POST']))
        );

        $config->set('http.cors.allowed-methods', 10);

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []));
    }

    public function testAllowedHeaders()
    {
        $config = new Config(new Lifecycle(__DIR__));
        $c = new Cors();

        $config->set('http.cors.allowed-headers', 'Parek');

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Headers' => 'Parek']))
        );

        $config->set('http.cors.allowed-headers', ['Parek', 'Rohlik']);

        $this->assertThat(
            $c->handle($config, new Request('/', '', 'GET', [], '', [])),
            $this->equalTo(new EmptyResponse(headers: ['Access-Control-Allow-Headers' => 'Parek, Rohlik']))
        );

        $config->set('http.cors.allowed-headers', 10);

        $this->expectException(ConfigException::class);
        $c->handle($config, new Request('PAREKVROHLIKU', '', 'GET', [], '', []));
    }
}
