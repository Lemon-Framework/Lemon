<?php

declare(strict_types=1);

// does stuff it just works ok
// I really regret everyone who will ever read it
// Majkel 1645651386

use Lemon\Support\Filesystem;
use Lemon\Support\Types\Arr;

define('LEMON_NO_INIT', true);

require __DIR__.'/../vendor/autoload.php';

$argv = array_slice($argv, 1);

if (!isset($argv[0])) {
    exit('Expected file name');
}

$class = '\\'.str_replace('.', '\\', $argv[0]);

if (!class_exists($class)) {
    exit('Class does not exist');
}

$ref = new ReflectionClass($class);
$doc = $ref->getDocComment();
preg_match('/@see\s+(\\\\.+)/', $doc, $matches);
$target = $matches[1];

$ref_target = new ReflectionClass($target);

$target_filename = $ref_target->getFileName();

$target_file = Filesystem::read($target_filename);

$functions = [];

preg_replace_callback('/\s*\/\*\*\n\s*\*\s(.+?)\n[\s\S]+?\*\/\n\s*public\s*function\s+(.*?\(.*?\))(?:\s*:\s*(.*?))\s/m', static function ($matches) use (&$functions, $target): void {
    $functions[] = ['self' === $matches[3] ? $target : $matches[3], $matches[2], $matches[1]];
}, $target_file);

$file = $ref->getFileName();

Filesystem::write($file, preg_replace(
    '/\s*\*\s*@see.+/',
    PHP_EOL
    .implode(
        PHP_EOL,
        array_map($functions, static fn ($item) => ' * @method static '.implode(' ', $item))->content
    )
    .PHP_EOL
    .' *'
    .PHP_EOL
    .' * @see '
    .$target,
    Filesystem::read($file)
));
