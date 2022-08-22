<?php

declare(strict_types=1);

namespace Lemon\Testing;

use Lemon\Contracts\Templating\Factory;
use Lemon\Http\Response;
use Lemon\Templating\Template;

final class TestResponse
{
    public function __construct(
        public readonly Response $response,
        private TestCase $testCase,
        private Factory $factory,
    ) {
    }

    public function assertStatus(int $expected): void
    {
        $this->testCase->assertSame($expected, $this->response->code());
    }

    public function assertOK(): void
    {
        $this->assertStatus(200);
    }

    public function assertBody(mixed $expected): void
    {
        $this->testCase->assertSame($expected, $this->response->body);
    }

    public function assertTemplate(string $expected_name): void
    {
        if (!$this->response->body instanceof Template) {
            $this->testCase->fail('Failed asserting that response body is template');
        }

        $path = $this->factory->getRawPath($expected_name);

        $this->testCase->assertSame($path, $this->response->body->raw_path);
    }

    public function assertHeader(string $header, string $expected): void
    {
        $this->testCase->assertSame($expected, $this->response->header($header));
    }

    public function assertLocation(string $expected): void
    {
        $this->assertHeader('Location', $expected);
    }

    public function assertCookie(string $cookie, string $expected): void
    {
        $this->testCase->assertSame(
            $expected,
            array_filter($this->response->cookies(), fn ($item) => $item[0] === $cookie)[0][1] ?? null
        );
    }
}
