<?php

declare(strict_types=1);

namespace Lemon\Testing;

use Lemon\Contracts\Http\Session;
use Lemon\Contracts\Templating\Factory;
use Lemon\Http\Request;
use Lemon\Kernel\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Base TestCase for testing Lemon apps.
 * Requires phpunit.
 */
abstract class TestCase extends BaseTestCase
{
    protected Application $application;

    protected function setUp(): void
    {
        $this->application = $this->createApplication();
    }

    abstract public function createApplication(): Application;

    public function request(string $path, string $method = 'GET', array $headers = [], array $cookies = [], string $body = ''): TestResponse
    {
        [$path, $query] = Request::trimQuery($path);
        $request = new Request($path, $query, $method, $headers, $body, $cookies);

        $app = $this->application;
        $app->add(Request::class, $request);
        $app->alias('request', Request::class);

        return new TestResponse(
            $app->get('routing')->dispatch($request),
            $this,
            $app->get(Factory::class)
        );
    }

    public function session(...$data): static
    {
        $this->application->add(SessionMock::class, new SessionMock($data));
        $this->application->alias(Session::class, SessionMock::class);
        $this->application->alias('session', SessionMock::class);

        return $this;
    }
}