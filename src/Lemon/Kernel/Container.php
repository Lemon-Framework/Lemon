<?php

declare(strict_types=1);

namespace Lemon\Kernel;

use Lemon\Kernel\Exceptions\ContainerException;
use Lemon\Kernel\Exceptions\NotFoundException;
use Lemon\Support\Types\Arr;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    /**
     * Container services.
     *
     * @var array<string, object>
     */
    private array $services = [];

    /**
     * Service aliases.
     *
     * @var array<string, string>
     */
    private array $aliases = [];

    public function __construct()
    {
    }

    /**
     * Returns service of given class/alias.
     *
     * @throws \Lemon\Kernel\Exceptions\NotFoundException
     */
    public function get(string $id): mixed
    {
        if (!Arr::hasKey($this->services, $id)) {
            if (!Arr::hasKey($this->aliases, $id)) {
                throw new NotFoundException('Service '.$id.' does not exist');
            }
            $id = $this->aliases[$id];
        }

        if (!$this->services[$id]) {
            $this->services[$id] = $this->make($id);
        }

        return $this->services[$id];
    }

    /**
     * Adds new service.
     *
     * @throws \Lemon\Kernel\Exceptions\ContainerException
     * @throws \Lemon\Kernel\Exceptions\NotFoundException
     */
    public function add(string $service): static
    {
        if (!class_exists($service)) {
            throw new NotFoundException('Class '.$service.' does not exist');
        }
        if (Arr::has($this->services, $service)) {
            throw new ContainerException('Service '.$service.' is already registered');
        }
        $this->services[$service] = null;

        return $this;
    }

    /**
     * Creates new alias.
     *
     * @throws \Lemon\Kernel\Exceptions\NotFoundException
     */
    public function alias(string $alias, string $class): static
    {
        if (!$this->has($class)) {
            throw new NotFoundException('Service '.$class.' does not exist');
        }
        $this->aliases[$alias] = $class;

        return $this;
    }

    /**
     * Returns all registered services.
     */
    public function services(): array
    {
        return Arr::keys($this->services)->content;
    }

    /**
     * Returns whenever service exist.
     */
    public function has(string $id): bool
    {
        return Arr::hasKey($this->services, $id);
    }

    /**
     * Returns whenever service exist.
     */
    public function hasAlias(string $id): bool
    {
        return Arr::hasKey($this->aliases, $id);
    }

    /**
     * Creates service instance of given class.
     */
    private function make(string $service): mixed
    {
        $class = new ReflectionClass($service);
        $constructor = $class->getConstructor();

        if (!$constructor) {
            return new $service();
        }

        $class_params = $constructor->getParameters();
        $params = [];

        foreach ($class_params as $param) {
            $type = (string) $param->getType();
            $params[] = $type === static::class ? $this : $this->get($type);
        }

        return new $service(...$params);
    }

    // public funciton call(callable $callback): mixed
}
