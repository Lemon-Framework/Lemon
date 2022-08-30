<?php

declare(strict_types=1);

namespace Lemon\Testing;

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
    private ?Application $application = null;

    abstract public function createApplication(): Application;

    public function request(string $path, string $method = 'GET', array $headers = [], array $cookies = [], string $body = ''): TestResponse
    {
        [$path, $query] = Request::trimQuery($path);
        $request = new Request($path, $query, $method, $headers, $body, $cookies);

        $app = $this->getApplication();
        $app->add(Request::class, $request);
        $app->alias('request', Request::class);

        return new TestResponse(
            $app->get('routing')->dispatch($request),
            $this,
            $app->get(Factory::class)
        );
    }

    public function getApplication(): Application
    {
        if (!$this->application) {
            $this->application = $this->createApplication();
        } 

        return $this->application;
    }
}
