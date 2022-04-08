<?php

declare(strict_types=1);

namespace Lemon\Cache;

use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem as FS;
use Lemon\Support\Types\Str;

class Cache
{
    /**
     * Current lifecycle instance
     */
    private Lifecycle $lifecycle;

    /**
     * Cached data
     */
    private array $data = [];

    /**
     * Path of data file
     */
    private string $data_path;

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
        $this->load();
    }

    /**
     * Loads cache, creates files if they dont exist
     */
    public function load(): void
    {
        $directory = $this->lifecycle->config('cache', 'storage');
        $path = $this->lifecycle->file($directory);
        $this->data_path = FS::join($path, 'data.json');
        if (! FS::isDir($directory)) {
            FS::makeDir($path);
            FS::write(FS::join($path, '.gitignore'), "*\n!.gitignore");
            FS::write($this->data_path, '{}');
        }
        
        $this->data = json_decode(FS::read($this->data_path), true);
        
    }

    /**
     * Writes data to cache at the end
     */
    public function __destruct()
    {
        FS::write($this->data_path, json_encode($this->data));
    }

    /**
     * Returns cached data
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Returns data for given key, if not set calls given function
     */
    public function get(string $key, callable $callback=null): mixed
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if ($callback) {
            return $callback($this);
        }

        return null;
    }

    /**
     * Sets value for given key
     */
    public function set(mixed $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Removes given key
     */
    public function remove(mixed $key): self
    {
        unset($this->data[$key]);
        return $this;
    }

    /**
     * Clears cache
     */
    public function clear(): void
    {
        $this->data = [];
    }
}
