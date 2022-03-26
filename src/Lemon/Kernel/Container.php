<?php
// TODO tests
declare(strict_types=1);

namespace Lemon\Kernel;

use Lemon\Exceptions\ContainerException;
use Lemon\Support\Types\Arr;
use ReflectionClass;

class Container 
{
    private array $instances = [];

    private array $services = [];

    /**
     * Returns service of given class
     *
     * @throws \Lemon\Exceptions\ContainerException 
     */
    public function getService(string $service): mixed
    {
        if (! Arr::has($this->services, $service)) {
            throw new ContainerException('Service '.$service.' does not exist');
        }

        if (! isset($this->instances[$service])) {
            $this->instances[$service] = $this->makeService($service);
        }

        return $this->instances[$service];
    }

    /**
     * Creates service instance of given class
     *
     * @throws \Lemon\Exceptions\ContainerException 
     */
    private function makeService(string $service): mixed 
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
            $params[] = $type === static::class ? $this : $this->getService($type);
        }
        return new $service(...$params);
    }

    /**
     * Adds new service
     *
     * @throws \Lemon\Exceptions\ContainerException
     */
    public function addService(string $service): static
    {
        if (! class_exists($service)) {
            throw new ContainerException('Class '.$service.' does not exist');
        } 
        if (Arr::has($this->services, $service)) {
            throw new ContainerException('Service '.$service.' is already registered');
        }
        $this->services[] = $service;
        
        return $this;
    }

    public function getAllServices(): array
    {
        return $this->services;
    }
}
