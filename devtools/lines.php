<?php

declare(strict_types=1);

if (!function_exists('countLines')) {
    if (!function_exists('countLines')) {
        function countLines($directory)
        {
            $lines = 0;

            foreach (scandir($directory) as $file) {
                if (in_array($file, ['.', '..'])) {
                    continue;
                }

                $path = $directory.DIRECTORY_SEPARATOR.$file;

                if (is_file($path)) {
                    $file_lines = count(file($path));
                    echo $path.': '.$file_lines.PHP_EOL;
                    $lines += $file_lines;
                }

                if (is_dir($path)) {
                    $lines += countLines($path);
                }
            }

            return $lines;
        }
    }
}

echo countLines($argv[1]);
