<?php

declare(strict_types=1);

namespace Lemon\Tree;

use Lemon\Contracts\Tree\Manager as ManagerContract;
use Lemon\DataMapper\DataMapper;

class Manager implements ManagerContract
{
    /**
     * {@inheritdoc}
     */
    public function find(string $entity, string $key, mixed $value): ?object
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(object $entity): static
    {
        return $this;
    }
}
