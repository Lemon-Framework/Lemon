<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel\Resources\Units;

use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Container;

class User implements Injectable
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {

    }

    public static function fromInjection(Container $container, mixed $value): self
    {
        return $container->get(UserFactory::class)->make($value);
    }
}
