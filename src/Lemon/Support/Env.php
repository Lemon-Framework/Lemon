<?php

declare(strict_types=1);

namespace Lemon\Support;

use Exception;
use Lemon\Config\Config;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Str;

final class Env
{
    private array $data;

    private string $path;

    public function __construct(
        private Config $config,
        private Lifecycle $lifecycle
    ) {
        $this->path = $config->part('kernel')->get('env_path');
        $this->load();
    }

    public function __destruct()
    {
        $this->commit();
    }

    public function load(): void
    {
        $content = Filesystem::read($this->path);
        foreach (Str::split($content, PHP_EOL) as $line) {
            $data = Str::split($line, '=');
            if (2 != count($data)) {
                throw new Exception('Env file does not contain valid data');
            }
            $this->data[$data[0]] = $data[1];
        }
    }

    public function get(string $key): string
    {
        if (!$this->has($key)) {
            throw new Exception('Env key '.$key.' does not exist');
        }

        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return Arr::hasKey($this->data, $key);
    }

    public function set(string $key, string $value): void
    {
        $this->data[$key] = $value;
    }

    public function commit(): void
    {
        $result = '';

        foreach ($this->data as $key => $value) {
            $result .= "{$key}={$value}".PHP_EOL;
        }

        Filesystem::write($this->path, $result);
    }
}
