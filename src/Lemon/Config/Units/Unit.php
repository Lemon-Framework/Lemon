<?php

namespace Lemon\Config\Units;

use Exception;

/**
 * Represents config Unit
 */
abstract class Unit
{
    /**
     * Config data of Unit
     *
     * @var array $data
     */
    protected array $data;

    /**
     * Creates new Unit instance, sets pre-defined values
     */
    abstract public function __construct();

    public function __get($name)
    {
        if (!isset($this->data[$name])) {
            throw new Exception('Undefined config value ' . $name);
        }

        return $this->data[$name];
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
}
