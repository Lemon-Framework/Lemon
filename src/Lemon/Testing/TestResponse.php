<?php

declare(strict_types=1);

namespace Lemon\Testing;

use Lemon\Http\Response;

final class TestResponse
{
    public function __construct(
        private Response $response,
        private TestCase $testCase,
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

    public function assertBody(string $exoected): void
    {
        $this->testCase->assertSame($exoected, $this->response->body);
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
        $this->testCase->assertSame($expected, 
            array_filter($this->response->cookies(), fn($item) => $item[0] === $cookie)[0][1] ?? null
        );
    }
}
