<?php

declare(strict_types=1);

namespace Lemon\Kernel;

use Lemon\Contracts\Kernel\Injectable;
use Lemon\Kernel\Exceptions\ContainerException;
use Lemon\Kernel\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * Container services.
     *
     * @var array<string, ?object>
     */
    private array $services = [];

    /**
     * Service aliases.
     *
     * @var array<string, string>
     */
    private array $aliases = [];

    /**
     * Returns service of given class/alias.
     *
     * @throws \Lemon\Kernel\Exceptions\NotFoundException
     */
    public function get(string $id, mixed $value = null): mixed
    {
        $id = trim($id, '?');
        if ($this->isInjectable($id)) {
            return $id::fromInjection($this, $value);
        }

        if (!array_key_exists($id, $this->services)) {
            if (!array_key_exists($id, $this->aliases)) {
                throw new NotFoundException('Service '.$id.' does not exist');
            }
            $id = $this->aliases[$id];
        }

        if (is_null($this->services[$id])) {
            $this->services[$id] = $this->make($id, $value);
        }

        return $this->services[$id];
    }

    /**
     * Adds new service.
     *
     * @throws \Lemon\Kernel\Exceptions\NotFoundException
     */
    public function add(string $service, object $instance = null): static
    {
        if (!class_exists($service)) {
            throw new NotFoundException('Class '.$service.' does not exist');
        }

        $this->services[$service] = $instance;

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
        return array_keys($this->services);
    }

    /**
     * Returns whenever service exist.
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * Returns whenever service exist.
     */
    public function hasAlias(string $id): bool
    {
        return array_key_exists($id, $this->aliases);
    }

    public function call(callable $callback, array $params): mixed
    {
        $fn = is_array($callback) ? new \ReflectionMethod(...$callback) : new \ReflectionFunction($callback);
        $injected = [];
        foreach ($fn->getParameters() as $param) {
            if ($class = (string) $param->getType()) {
                $injected[$param->getName()] = $this->get($class, $params[$param->getName()] ?? null);
            } elseif (isset($params[$param->getName()])) {
                $injected[$param->getName()] = $params[$param->getName()];
            } elseif (!$param->isOptional()) {
                return new ContainerException('Parameter '.$param->getName().' is missing');
            }
        }

        $action = new \Fiber($callback);

        return $action->start(...$injected) ?? $action->getReturn();
    }

    /**
     * Returns all aliases.
     */
    public function aliases(): array
    {
        return $this->aliases;
    }

    public function isInjectable(string $id): bool
    {
        return
            class_exists($id)
            ? in_array(Injectable::class, class_implements($id))
            : false
        ;
    }

    /**
     * Creates service instance of given class.
     */
    private function make(string $service, mixed $value = null): mixed
    {
        $class = new \ReflectionClass($service);
        $constructor = $class->getConstructor();

        if (!$constructor) {
            return new $service();
        }

        $class_params = $constructor->getParameters();
        $params = [];

        foreach ($class_params as $param) {
            $type = (string) $param->getType();
            $params[] = $this->get($type);
        }

        return new $service(...$params);
    }
}
