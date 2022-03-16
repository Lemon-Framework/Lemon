<?php

declare(strict_types=1);

if (! function_exists('loader')) {
    /**
     * Loads all files from specific folder
     * Inspired by loader from CoolFido.
     */
    function loader(string $dir): void
    {
        foreach (scandir($dir) as $file) {
            $path = $dir.DIRECTORY_SEPARATOR.$file;
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            if (preg_match('/\\.php$/', $file)) {
                require_once $path;
            }

            if (is_dir($path)) {
                loader($path);
            }
        }
    }
}
