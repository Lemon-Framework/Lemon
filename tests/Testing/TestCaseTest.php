<?php

declare(strict_types=1);

namespace Lemon\Tests\Testing;

use Lemon\Testing\TestResponse;

/**
 * @internal
 * @coversNothing
 */
class TestCaseTest extends TestCase
{
    public function testRequest()
    {
        $this->assertInstanceOf(TestResponse::class, $this->request('/'));
    }
}
