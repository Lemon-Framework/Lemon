<?php

declare(strict_types=1);

namespace Lemon\Support;

use Lemon\Exceptions\FilesystemException;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Str;

class Filesystem
{
    /**
     * Returns content of given file.
     */
    public static function read(string $file): string
    {
        if (self::isFile($file)) {
            return file_get_contents($file);
        }

        throw FilesystemException::explainFileNotFound($file);
    }

    /**
     * Writes content to given file.
     */
    public static function write(string $file, string $content): void
    {
        file_put_contents($file, $content);
    }

    /**
     * Creates new directory.
     */
    public static function makeDir(string $dir): void
    {
        mkdir($dir, recursive: true);
    }

    /**
     * Returns array of paths in given directory.
     */
    public static function listDir(string $dir): array
    {
        if (! self::isDir($dir)) {
            throw FilesystemException::explainDirectoryNotFound($dir);
        }

        $result = [];
        foreach (scandir($dir) as $file) {
            $file = Filesystem::join($dir, $file);
            if (Filesystem::isFile($file)) {
                $result[] = $file;
            }

            if (Filesystem::isDir($file)) {
                $result = Arr::merge(
                    $result,
                    self::listDir($file)
                );
            }
        }

        return $result;
    }

    /**
     * Returns whenever given path is file.
     */
    public static function isFile(string $file): bool
    {
        return is_file($file);
    }

    /**
     * Returns whenever given path is directory.
     */
    public static function isDir(string $dir): bool
    {
        return is_dir($dir);
    }

    /**
     * Deletes given file/directory.
     */
    public static function delete(string $file): void
    {
        if (self::isFile($file)) {
            unlink($file);
        }

        if (self::isDir($file)) {
            foreach (scandir($file) as $sub) {
                self::delete(self::join($file, $sub));
            }
            rmdir($file);
        }
    }

    /**
     * Joins given paths with directory separator.
     */
    public static function join(string ...$paths): Types\String_
    {
        return Str::join(
            DIRECTORY_SEPARATOR,
            $paths
        );
    }

    /**
     * Converts path into os-compatible.
     *
     * @param string $path
     *                     return string
     */
    public static function normalize(string $path): string
    {
        $path = rtrim($path, '/\\');

        return preg_replace('/(\\/|\\\)/', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Returns parent of given path.
     */
    public static function parent(string $path)
    {
        $path = self::normalize($path);

        return Str::join(
            DIRECTORY_SEPARATOR,
            Str::split($path, DIRECTORY_SEPARATOR)->slice(0, -2)->content
        );
    }
}
