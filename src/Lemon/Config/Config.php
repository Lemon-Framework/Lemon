<?php

declare(strict_types=1);

namespace Lemon\Config;

use Lemon\Config\Exceptions\ConfigException;
use Lemon\Support\Filesystem;
use Lemon\Support\Properties\Properties;
use Lemon\Support\Properties\Read;
use Lemon\Support\Types\Array_;
use Lemon\Support\Types\Str;

/**
 * The Lemon Config Manager.
 *
 * @property array<string, mixed> $data
 * @property array<string, string> $parts 
 */
class Config
{

    use Properties;

    #[Read]
    private array $data = [];

    #[Read]
    private array $parts = [];

    public function part(string $name): Array_
    { 
        $name = Str::toLower($name)->value;
        if (! isset($this->data[$name])) {
            $path = $this->parts[$name] ?? Filesystem::join(__DIR__, '..', Str::capitalize($name)->value, 'config.php');
            if (! Filesystem::isFile($path)) {
                throw new ConfigException('Config part '.$name.' does not exist');
            }

            $data = require $path;
            if (! is_array($data)) {
                throw new ConfigException('Config file for part '.$name.' does not return array');
            }
            $this->data[$name] = new Array_($data);
        }

        return $this->data[$name];
    }

    public function load(string $directory): static
    {
        if (! Filesystem::isDir($directory)) {
            throw new ConfigException('Directory '.$directory.' does not exist');
        }
        foreach (Filesystem::listDir($directory) as $path) {
            static $s = DIRECTORY_SEPARATOR;
            if (preg_match('/^'.Str::replace($directory, $s, '\\'.$s)->value.'\\'.$s.'(.+?)\.php$/', $path, $matches)) {
                $key = Str::replace($matches[1], $s, '.')->value;
                $this->parts[$key] = $path;
            }
        }
        return $this;
    }
}
