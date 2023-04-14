<?php

declare(strict_types=1);

namespace Lemon\Tree;

use Lemon\Contracts\Tree\Manager as ManagerContract;
use Lemon\DataMapper\DataMapper;
use Lemon\Kernel\Application;
use Lemon\Support\CaseConverter;
use Lemon\Support\Filesystem;

class Manager implements ManagerContract
{
    private array $data = [];

    public function __construct(
        private Application $app,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $entity, string $key, mixed $value): ?object
    {
        $this->load($entity);

        if ($key === 'id') {
            return DataMapper::mapTo($this->data[$entity][$value], $entity) ?? null;
        }

        foreach ($this->data[$entity] as $item) {
            if ($item[$key] === $value) {
                return DataMapper::mapTo($item, $entity);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(object $entity): static
    {
        $class = $entity::class;
        $this->load($class);
        
        $data = (array) $entity;
        $id = $data['id'] ?? null;

        if ($id === null) {
            $this->data[] = $data;
            return $this;
        }

        $this->data[$id] = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $entity, string $key, mixed $value): static
    {
        $this->load($entity);

        if ($key === 'id') {
            unset($this->data[$entity][$value]);
            return $this;
        }

        foreach ($this->data[$entity] as $id => $item) {
            if ($item[$key] === $value) {
                unset($this->data[$entity][$id]);
                return $this;
            }
        }

        return $this;
    }

    private function load(string $entity): void
    {
        if (!property_exists($entity, 'id')) {
            throw new EntityMissingIdException('Entity '.$entity.' is missing an id property'); 
        }

        if (!isset($this->data[$entity])) {
            return;
        }

        $file = $this->getFile($entity);

        if (!Filesystem::isFile($file)) {
            $this->data[$entity] = [];
            return;
        }

        $this->data[$entity] = require $file;
    }

    private function getStorage(): string
    {
        $file = $this->app->file('storage.tree');

        if (!Filesystem::isDir($file)) {
            Filesystem::makeDir($file);
        }

        return $file;
    }

    private function getFile(string $entity): string
    {
        $entity = str_replace('\\', '_', $entity);
        $name = 'entities_'.CaseConverter::toSnake($entity);
        return Filesystem::join($this->getStorage(), $name).'.php';
    }

}
