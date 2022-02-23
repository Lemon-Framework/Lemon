<?php

// does stuff it just works ok
// I really regret everyone who will ever read it
// Majkel 1645651386

use Lemon\Support\Filesystem;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Str;

define('LEMON_NO_INIT', true);

require __DIR__ . '/../vendor/autoload.php';

$argv = array_slice($argv, 1);

if (!isset($argv[0]))
    die('Expected file name');

$class = '\\' . str_replace('.', '\\', $argv[0]);

if (!class_exists($class))
    die('Class does not exist');

$ref = new ReflectionClass($class);
$doc = $ref->getDocComment();
preg_match('/@see\s+(\\\\.+)/', $doc, $matches);
$target = $matches[1];

$ref_target = new ReflectionClass($target);

$target_filename = $ref_target->getFileName();

$target_file = Filesystem::read($target_filename);

$functions = [];

preg_replace_callback('/\s*\/\*\*\n\s*\*\s(.+?)\n[\s\S]+?\*\/\n\s*public\s*function\s+(.*?\(.*?\))(?:\s*:\s*(.*?))\s/m', function($matches) use (&$functions, $target) {
    $functions[] = [($matches[3] == 'self' ? $target : $matches[3]), $matches[2], $matches[1]];
}, $target_file);

$file = $ref->getFileName();

Filesystem::write($file, preg_replace('/\s*\*\s*@see.+/', 
    PHP_EOL 
    . Str::join(
        PHP_EOL,
        Arr::map($functions, fn($item) => ' * @method static ' . Str::join(' ', $item))->content
    )
    . PHP_EOL
    . ' *'
    . PHP_EOL
    . ' * @see ' 
    . $target, 
    Filesystem::read($file)
));
