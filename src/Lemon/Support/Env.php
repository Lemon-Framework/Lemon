<?php

declare(strict_types=1);

namespace Lemon\Support;

use Exception;
use Lemon\Contracts\Support\Env as EnvContract;
use Lemon\Kernel\Application;
use Lemon\Support\Types\Str;

final class Env implements EnvContract
{
    private array $data = [];

    private string $path;

    private bool $changed = false;

    public function __construct(
        private Application $application
    ) {
        $this->path = $application->directory.DIRECTORY_SEPARATOR.'.env';
        $this->load();
    }

    public function __destruct()
    {
        $this->commit();
    }

    /**
     * Loads env file.
     */
    public function load(): void
    {
        // For replit mostly
        if (!is_file($this->path)) {
            $this->data = $_ENV;
            return;
        }

        $content = str_replace("\r\n", "\n", Filesystem::read($this->path)); // @windows dekujeme za nazor, posilame klicenku
        foreach (Str::split($content, "\n") as $line) {
            if (!$line) {
                continue;
            }
            $data = Str::split($line, '=');
            if (2 != count($data)) {
                throw new Exception('Env file does not contain valid data');
            }
            $this->data[$data[0]] = $data[1] ?: null;
        }
    }

    /**
     * Returns env value of given key or default if not present.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->data[$key];
    }

    /**
     * Returns file with name from env.
     */
    public function file(string $key, string $prefix, mixed $default = null): string
    {
        return $this->application->file($this->get($key, $default), $prefix);
    }

    /**
     * Returns whenever env key exist.
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Sets env key with given value.
     */
    public function set(string $key, string $value): void
    {
        if (!$this->changed) {
            $this->changed = true;
        }
        $this->data[$key] = $value;
    }

    /**
     * Returns env data.
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Saves data back to env file.
     */
    public function commit(): void
    {
        if (!$this->changed || !is_file($this->path)) {
            return;
        }

        $result = '';

        foreach ($this->data as $key => $value) {
            $result .= "{$key}={$value}".PHP_EOL;
        }

        Filesystem::write($this->path, $result);
    }
}
