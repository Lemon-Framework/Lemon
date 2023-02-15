<?php

declare(strict_types=1);

namespace Lemon\Tests\Kernel\Resources\Units;

class UserFactory
{
    public function make(string $name): User
    {
        return new User(1, $name);
    }
}
