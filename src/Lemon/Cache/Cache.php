<?php

declare(strict_types=1);

namespace Lemon\Cache;

use DateInterval;
use DateTime;
use Lemon\Cache\Exceptions\InvalidArgumentException;
use Lemon\Contracts\Cache\Cache as CacheContract;
use Lemon\Contracts\Config\Config;
use Lemon\Kernel\Application;
use Lemon\Support\Filesystem as FS;

class Cache implements CacheContract
{
    protected int $time;

    /**
     * Cached data.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $data = [];

    /**
     * Path of data file.
     */
    private string $data_path;

    public function __construct(
        private Application $application,
        private Config $config
    ) {
        $this->time = time();
        $this->load();
    }

    /**
     * Writes data to cache at the end.
     */
    public function __destruct()
    {
        $this->commit();
    }

    /**
     * Loads cache, creates files if they dont exist.
     */
    public function load(): void
    {
        $directory = $this->config->get('cache.storage');
        $path = $this->application->file($directory);
        $this->data_path = FS::join($path, 'data.json');
        if (!FS::isDir($path)) {
            FS::makeDir($path);
            FS::write(FS::join($path, '.gitignore'), "*\n!.gitignore");
            FS::write($this->data_path, '{}');
        }

        $this->data = json_decode(FS::read($this->data_path), true);
    }

    /**
     * Returns cached data.
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Returns cached value or default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)) {
            $value = $this->data[$key];
            if (!$value['expires_at'] || $value['expires_at'] > $this->time) {
                return $value['value'];
            }
            $this->delete($key);
        }

        return $default;
    }

    /**
     * Returns cached value or executes given action.
     */
    public function retreive(string $key, callable $action): mixed
    {
        if ($result = $this->get($key)) {
            return $result;
        }

        $new = $this->application->call($action, []);

        $this->set($key, $new);

        return $new;
    }

    /**
     * Sets new value to cache.
     */
    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        $expires_at = null;
        if ($ttl) {
            if (is_int($ttl)) {
                if ($ttl <= 0) {
                    throw new InvalidArgumentException('TTL must be bigger than 0');
                }
            }
            $expires_at = new DateTime('@'.$this->time);
            $ttl = $ttl instanceof DateInterval ? $ttl : new DateInterval("PT{$ttl}S");
            $expires_at = $expires_at->add($ttl)->getTimestamp();
        }
        $this->data[$key] = ['value' => $value, 'expires_at' => $expires_at];

        return true;
    }

    /**
     * Removes value from cache.
     */
    public function delete(string $key): bool
    {
        if (!$this->has($key)) {
            throw new InvalidArgumentException('Item '.$key.' does not exist');
        }
        unset($this->data[$key]);

        return true;
    }

    /**
     * Clears cache.
     */
    public function clear(): bool
    {
        $this->data = [];

        return true;
    }

    /**
     * Returns value for every given key.
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            if (!is_string($key)) {
                throw new InvalidArgumentException('Argument 1 must contain only strings');
            }
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * Removes item for every given key.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            if (!is_string($key)) {
                throw new InvalidArgumentException('Given keys must be type string');
            }
            $this->delete($key);
        }

        return true;
    }

    /**
     * Sets multiple items.
     */
    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidArgumentException('Argument 1 must contain only strings');
            }
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * Returns whenever key exist.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Saves data to file.
     */
    public function commit(): bool
    {
        return FS::write($this->data_path, json_encode($this->data));
    }
}
