<?php

namespace Lemon\Support;

use Lemon\Exceptions\DirectoryNotFoundException;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;
use Lemon\Support\Types\Str;

class Filesystem
{
    public static function read(string $file): string
    {
        return file_get_contents($file);
    }

    public static function write(string $file, string $content): string
    {
        return file_put_contents($file, $content);
    }

    public static function makeDir(string $dir): void
    {
        mkdir($dir, recursive: true);
    }

    public static function listDir(string $dir): array
    {
        if (!self::isDir($dir)) {
            throw DirectoryNotFoundException::explain($dir); 
        }
        $result = [];
        foreach (scandir($dir) as $file) {
            $file = Filesystem::join($dir, $file);
            if (Filesystem::isFile($file)) {
                $result[] = $file; 
            }

            if (Filesystem::isDir($file)) {
                $result = Arr::merge($result, 
                    self::listDir($file)
                ); 
            }
        }
        return $result;
    }

    public static function isFile(string $file)
    {
        return is_file($file);
    }

    public static function isDir(string $dir) {
        return is_dir($dir);
    }

    public static function delete(string $file)
    {

        if (self::isFile($file))
            unlink($file);

        if (self::isDir($file))
        {
            foreach (scandir($file) as $sub)
                self::delete(self::join($file, $sub));
            rmdir($file);
        }
    }

    public static function join(string ...$paths)
    {
        return Str::join(DIRECTORY_SEPARATOR, 
            $paths
        );
    }

    public static function normalize(string $path)
    {
        $path = rtrim($path, '/\\');

        $path = preg_replace('/(\\/|\\\)/', DIRECTORY_SEPARATOR, $path);

        return $path;
    }

    public static function parent(string $path)
    {
        $path = self::normalize($path);

        return Str::join(DIRECTORY_SEPARATOR, 
            Str::split($path, DIRECTORY_SEPARATOR)->slice(0, -1)->content
        );
    }

}

