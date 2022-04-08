<?php
// TODO tests
declare(strict_types=1);

namespace Lemon\Kernel;

use Lemon\Exceptions\ContainerException;
use Lemon\Support\Types\Arr;
use ReflectionClass;

class Container 
{
    /**
     * Container services
     *
     * @var array<string, mixed> $services
     */
    private array $services = [];

    /**
     * Service aliases
     *
     * @var array<string, string> $aliases
     */
    private array $aliases = [];

    /**
     * Returns service of given class/alias
     *
     * @throws \Lemon\Exceptions\ContainerException 
     */
    public function get(string $id): mixed
    {
        if (! Arr::hasKey($this->services, $id)) {
            if (! Arr::hasKey($this->aliases, $id)) {
                throw new ContainerException('Service '.$id.' does not exist');
            }
            $id = $this->aliases[$id];
        }

        if (! $this->services[$id]) {
            $this->services[$id] = $this->make($id);
        }

        return $this->services[$id];
    }

    /**
     * Creates service instance of given class
     *
     * @throws \Lemon\Exceptions\ContainerException 
     */
    private function make(string $service): mixed 
    {
        $class = new ReflectionClass($service);
        $constructor = $class->getConstructor();
        
        if (! $constructor) {
            return new $service;
        }

        $class_params = $constructor->getParameters();
        $params = [];

        foreach ($class_params as $param) {
            $type = (string) $param->getType();
            $params[] = $type === static::class ? $this : $this->get($type);
        }
        return new $service(...$params);
    }

    /**
     * Adds new service
     *
     * @throws \Lemon\Exceptions\ContainerException
     */
    public function add(string $service): static
    {
        if (! class_exists($service)) {
            throw new ContainerException('Class '.$service.' does not exist');
        } 
        if (Arr::has($this->services, $service)) {
            throw new ContainerException('Service '.$service.' is already registered');
        }
        $this->services[$service] = null;
        
        return $this;
    }

    /**
     * Creates new alias
     *
     * @throws \Lemon\Exceptions\ContainerException
     */
    public function alias(string $alias, string $class): static
    {
        if (!$this->has($class)) {
            throw new ContainerException('Service '.$class.' does not exist');
        }
        $this->aliases[$alias] = $class;
        return $this;
    }

    /**
     * Returns all registered services
     */
    public function services(): array
    {
        return Arr::keys($this->services)->content;
    }

    /**
     * Returns whenever service exist
     */
    public function has(string $id): bool
    {
        return Arr::hasKey($this->services, $id);
    }

    // public funciton call(callable $callback): mixed
}
