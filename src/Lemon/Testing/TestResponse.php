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

    public function assertStatus(int $expected): self
    {
        $this->testCase->assertSame($expected, $this->response->code());

        return $this;
    }

    public function assertOK(): self
    {
        $this->assertStatus(200);

        return $this;
    }

    public function assertBody(mixed $expected): self
    {
        $this->testCase->assertSame($expected, $this->response->body);

        return $this;
    }

    public function assertTemplate(string $expected_name, mixed ...$with): self
    {
        if (!$this->response->body instanceof Template) {
            $this->testCase->fail('Failed asserting that response body is template');
        }

        $path = $this->factory->getRawPath($expected_name);

        $this->testCase->assertSame($path, $this->response->body->raw_path);

        $data = $this->response->body->data;
        $this->testCase->assertEquals($data, $with);

        return $this;
    }

    public function assertHeader(string $header, string $expected): self
    {
        $this->testCase->assertSame($expected, $this->response->header($header));

        return $this;
    }

    public function assertLocation(string $expected): self
    {
        $this->assertHeader('Location', $expected);

        return $this;
    }

    public function assertCookie(string $cookie, string $expected): self
    {
        $this->testCase->assertSame(
            $expected,
            array_filter($this->response->cookies(), fn ($item) => $item[0] === $cookie)[0][1] ?? null
        );

        return $this;
    }
}
