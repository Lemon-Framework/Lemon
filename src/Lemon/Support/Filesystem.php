<?php

namespace Lemon\Support;

use Lemon\Support\Types\Array_;
use Lemon\Support\Types\Str;

class Filesystem
{
    public static function read(string $file)
    {
        return file_get_contents($file);
    }

    public static function write(string $file, string $content)
    {
        return file_put_contents($file, $content);
    }

    public static function isFile(string $file)
    {
        return is_file($file);
    }

    public static function delete(string $file)
    {

        if (is_file($file))
            unlink($file);

        if (is_dir($file))
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

