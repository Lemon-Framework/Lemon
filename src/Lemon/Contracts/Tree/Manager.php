<?php

declare(strict_types=1);

namespace Lemon\Contracts\Tree;

interface Manager
{
    /**
     * @template T
     * @param class-string<T> $entity
     * @return ?T
     */
    public function find(string $entity, string $key, mixed $value): ?object;

    public function save(object $entity): static;
}
