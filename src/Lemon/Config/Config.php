<?php

declare(strict_types=1);

namespace Lemon\Config;

use Lemon\Config\Exceptions\ConfigException;
use Lemon\Contracts\Config\Config as ConfigContract;
use Lemon\Kernel\Application;
use Lemon\Support\Filesystem;
use Lemon\Support\Types\Arr;

class Config implements ConfigContract
{
    private array $files = [];

    private array $data = [];

    public function __construct(
        private Application $application
    ) {
    }

    /**
     * Loads config data from given directory.
     */
    public function load(string $directory = 'config'): static
    {
        $directory = $this->application->file($directory);
        if (!Filesystem::isDir($directory)) {
            throw new ConfigException('Directory '.$directory.' does not exist');
        }
        static $s = DIRECTORY_SEPARATOR;
        foreach (Filesystem::listDir($directory) as $path) {
            $re = '/^'.preg_quote($directory.$s, '/').'(.+?)\.php$/';
            if (preg_match($re, $path, $matches)) {
                $key = str_replace($s, '.', $matches[1]);
                $this->files[$key] = $path;
            }
        }

        return $this;
    }

    /**
     * Returns value for given key in config.
     */
    public function get(string $key): mixed
    {
        $keys = explode('.', $key);

        $part = $keys[0];
        $keys = array_slice($keys, 1);

        $this->loadPart($part);
        $last = $this->data[$part];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $last)) {
                throw new ConfigException('Config key '.$key.' does not exist');
            }
            $last = $last[$key];
        }

        return $last;
    }

    /**
     * Returns project file for given key in config.
     */
    public function file(string $key, string $extension = null): string
    {
        return $this->application->file($this->get($key), $extension);
    }

    /**
     * Sets key in config for given value.
     */
    public function set(string $key, mixed $value): static
    {
        $keys = explode('.', $key);
        $part = $keys[0];

        $this->loadPart($part);
        $last = &$this->data[$part];
        foreach (array_slice($keys, 1, -1) as $key) {
            $last = &$last[$key];
        }
        $last[Arr::last($keys)] = $value;

        return $this;
    }

    /**
     * Loads part (if not loaded or force is true) into static::$data.
     */
    public function loadPart(string $part, bool $force = false): void
    {
        if (isset($this->data[$part]) && !$force) {
            return;
        }

        $path =
            $this->files[$part]
            ?? Filesystem::join(__DIR__, '..', ucfirst($part), 'config.php');

        if (!file_exists($path)) {
            throw new ConfigException('Part '.$part.' does not exist');
        }

        $this->data[$part] = require $path;
    }

    /**
     * Returns all config data.
     */
    public function data(): array
    {
        return $this->data;
    }
}
