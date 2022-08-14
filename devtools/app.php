<?php

use Lemon\Support\Filesystem;

require __DIR__.'/../vendor/autoload.php';

foreach (Filesystem::listDir(__DIR__.'/../src/') as $file) {
    $content = file_get_contents($file);
    $content = str_replace(['Lifecycle', 'lifecycle'], ['Application', 'application'], $content);

    file_put_contents($file, $content);
}

foreach (Filesystem::listDir(__DIR__.'/../tests/') as $file) {
    $content = file_get_contents($file);
    $content = str_replace(['Lifecycle', 'lifecycle'], ['Application', 'application'], $content);

    file_put_contents($file, $content);
}
