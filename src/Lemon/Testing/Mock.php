<?php

declare(strict_types=1);

namespace Lemon\Testing;

use Mockery\MockInterface;

class Mock
{
    public readonly MockInterface $mock;

    public function __construct(string $class)
    {
        // @phpstan-ignore-next-line
        $this->mock = \Mockery::mock($class);
    }

    public function expect(callable ...$methods): MockInterface
    {
        foreach ($methods as $method => $action) {
            // @phpstan-ignore-next-line
            $this->mock->shouldReceive($method)
                ->andReturnUsing(\Closure::fromCallable($action)->bindTo($this->mock))
            ;
        }

        return $this->mock;
    }
}
