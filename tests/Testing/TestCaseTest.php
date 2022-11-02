<?php

declare(strict_types=1);

namespace Lemon\Tests\Testing;

use Lemon\Testing\TestResponse;

/**
 * @internal
 *
 * @coversNothing
 */
class TestCaseTest extends TestCase
{
    public function testRequest()
    {
        $this->assertInstanceOf(TestResponse::class, $this->request('/'));
    }

    public function testMock()
    {
        $this->mock(Foo::class, 'foo')
            ->expect(bar: fn () => 'cs')
        ;

        $this->assertSame('cs', $this->application->get(Foo::class)->bar());
        $this->assertSame('cs', $this->application->get('foo')->bar());
    }
}

interface Foo
{
    public function bar(): string;
}
