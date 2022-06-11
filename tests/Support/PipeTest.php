<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Lemon\Support\Pipe;
use Lemon\Tests\TestCase;

class PipeTest extends TestCase
{
    public function testPipe()
    {
        $this->assertSame('Foo bar baz', 
            Pipe::send('foo.bar.baz')
                ->then('ucfirst')
                ->then(fn($value) => str_replace('.', ' ', $value))
                ->return()
        );

        $this->assertSame('foo bar', 
            Pipe::send('foo')
                ->with('bar')
                ->then(fn($value, $bar) => $value.' '.$bar)
                ->return()
        );
    }  
}
