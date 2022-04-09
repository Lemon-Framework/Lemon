<?php

declare(strict_types=1);

namespace Lemon\Cache;

use DateInterval;
use DateTime;
use Lemon\Cache\Exceptions\InvalidArgumentException;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem as FS;
use Lemon\Support\Types\Arr;
use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface
{
    /**
     * Current lifecycle instance
     */
    private Lifecycle $lifecycle;

    /**
     * Cached data
     *
     * @var array<string, array<string, mixed>>
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
        $this->commit();
    }

    /**
     * Returns cached data
     */
    public function data(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)) {
            return $this->data[$key]['value'];
        }

        if ($default) {
            return $default;
        }

        throw new InvalidArgumentException('Item '.$key.' does not exist');
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        $expires_at = null;
        if ($ttl) {
            $expires_at = new DateTime('now');
            $ttl = $ttl instanceof DateInterval ? $ttl : new DateInterval("P{$ttl}S");
            $expires_at = $expires_at->add($ttl);
        }
        $this->data[$key] = ['value' => $value, 'expires_at' => $expires_at];
        return true;
    }

    public function delete(string $key): bool
    {
        if (! $this->has($key)) {
            throw new InvalidArgumentException('Item '.$key.' does not exist');
        }
        unset($this->data[$key]);
        return true;
    }

    /**
     * Clears cache
     */
    public function clear(): bool
    {
        $this->data = [];
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            if (! is_string($key)) {
                throw new InvalidArgumentException('Argument 1 must contain only strings');
            }
            $result[] = $this->get($key);
        }
        return $result;
    }

    public function deleteMultiple(array $keys): bool
    {
        foreach ($keys as $key) {
            if (! is_string($key)) {
                throw new ('Given keys must be type string');
            }
            $this->delete($key);
        }
        return true;
    }

    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool
    { 
        foreach ($values as $key => $value) {
            if (! is_string($key)) {
                throw new InvalidArgumentException('Argument 1 must contain only strings');
            }
            $this->set($key, $value, $ttl);
        }       
        return true;
    }


    public function commit(): bool
    {
        return FS::write($this->data_path, json_encode($this->data));
    }


    public function has(string $key): bool
    {
        return Arr::hasKey($this->data, $key);
    }
}
