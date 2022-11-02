<?php

declare(strict_types=1);

namespace Lemon\Tests\Terminal\Commands;

use Lemon\Terminal\Commands\Command;
use Lemon\Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class CommandTest extends TestCase
{
    public function testConstruction()
    {
        $command = new Command('foo {bar} {baz}', function ($bar, $baz) {
            echo $bar.' '.$baz;
        });

        $this->assertSame([['obligated', 'bar'], ['obligated', 'baz']], $command->parameters);

        $this->assertNotEmpty($command->action);

        $command = new Command('foo {bar} {baz?}', function ($bar, $baz = 'foo') {
            echo $bar.' '.$baz;
        });

        $this->assertSame([['obligated', 'bar'], ['optional', 'baz']], $command->parameters);
    }
}
