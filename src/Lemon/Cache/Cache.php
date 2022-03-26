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

    public function __destruct()
    {
        FS::write($this->data_path, json_encode($this->data));
    }

    public function getData()
    {
        return $this->data;
    }

    public function get(string $key, callable $callback=null): ?mixed
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        $callback($this);
    }

    public function set(mixed $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function remove(mixed $key): self
    {
        unset($this->data[$key]);
        return $this;
    }

    public function clear(): void
    {
        $this->data = [];

    }
}
