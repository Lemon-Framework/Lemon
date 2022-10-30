<?php

declare(strict_types=1);

namespace Lemon\Support;

use Exception;
use Lemon\Support\Exceptions\FilesystemException;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Str;

class Filesystem
{
    /**
     * Returns content of given file.
     *
     * @throws \Lemon\Support\Exceptions\FilesystemException
     */
    public static function read(string $file): string
    {
        if (!is_file($file)) {
            throw FilesystemException::explainFileNotFound($file);
        }

        return file_get_contents($file);
    }

    /**
     * Writes content to given file.
     */
    public static function write(string $file, string $content): bool
    {
        return is_int(file_put_contents($file, $content));
    }

    /**
     * Creates new directory.
     *
     * @throws \Lemon\Support\Exceptions\FilesystemException
     */
    public static function makeDir(string $dir): void
    {
        if (is_dir($dir)) {
            throw new FilesystemException("Directory {$dir} already exist");
        }

        mkdir($dir, recursive: true);
    }

    /**
     * Returns array of paths in given directory.
     *
     * @throws \Lemon\Support\Exceptions\FilesystemException
     */
    public static function listDir(string $dir): array
    {
        if (!self::isDir($dir)) {
            throw FilesystemException::explainDirectoryNotFound($dir);
        }

        $result = [];
        foreach (scandir($dir) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $file = self::join($dir, $file);

            if (self::isFile($file)) {
                $result[] = $file;
            }

            if (self::isDir($file)) {
                if (count(scandir($file)) > 2) {
                    $result = array_merge(
                        $result,
                        self::listDir($file)
                    );
                } else {
                    $result[] = $file;
                }
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
                if (!in_array($sub, ['.', '..'])) {
                    self::delete(self::join($file, $sub));
                }
            }
            rmdir($file);
        }
    }

    /**
     * Joins given paths with directory separator.
     */
    public static function join(string ...$paths): string
    {
        if (count($paths) < 2) {
            throw new Exception('Function Filesystem::join takes at least 2 arguments');
        }

        $start = str_starts_with($paths[0], DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '';

        return $start.implode(
            DIRECTORY_SEPARATOR,
            array_map(
                static fn (string $item) => trim($item, DIRECTORY_SEPARATOR),
                $paths
            )
        );
    }

    /**
     * Converts path into os-compatible.
     */
    public static function normalize(string $path): string
    {
        $path = rtrim($path, '/\\');

        return preg_replace('/(\\/|\\\)+/', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Returns parent of given path.
     */
    public static function parent(string $path): string
    {
        $path = self::normalize($path);

        return implode(
            DIRECTORY_SEPARATOR,
            array_slice(explode(DIRECTORY_SEPARATOR, $path), 0, -1)
        );
    }
}
