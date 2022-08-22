<?php

declare(strict_types=1);

namespace Lemon\Tests\Testing;

use PHPUnit\Framework\AssertionFailedError;

/**
 * @internal
 * @coversNothing
 */
class TestResponseTest extends TestCase
{
    public function testStatus()
    {
        $this->request('/')->assertStatus(200);
        $this->request('/')->assertOK();
    }

    public function testBody()
    {
        $this->request('/')->assertBody('');
    }

    public function testTemplate()
    {
        $this->request('foo')->assertTemplate('foo.bar');
        $this->expectException(AssertionFailedError::class);
        $this->request('/')->assertTemplate('foo.bar');
    }

    public function testHeader()
    {
        $this->request('/')->assertHeader('Location', 'foo');
        $this->request('/')->assertLocation('foo');
    }

    public function testCookies()
    {
        $this->request('/')->assertCookie('foo', 'bar');
    }
}
