<?php

declare(strict_types=1);

namespace Lemon\Config;

use Lemon\Exceptions\Config as ConfigException;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem;
use Lemon\Support\Types\Array_;

/**
 * Main interface for storing config data in organised way.
 */
class Config
{
    public const BASE = 'Parts';

    /**
     * Lifecycle config unit belongs to.
     */
    public Lifecycle $lifecycle;

    public array $data = [];

    /**
     * Creates new config instance.
     */
    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
    }

    public function part(string $name): Array_
    {
        $path = Filesystem::join(__DIR__, self::BASE, $name).'.php';
        if (! Filesystem::isFile($path)) {
            throw new ConfigException('Config part '.$name.' does not exist');
        }

        if (! isset($this->data[$name])) {
            $this->data[$name] = new Array_(require_once $path);
        }

        return $this->data[$name];
    }
}
