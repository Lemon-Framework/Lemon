<?php

// Automaticaly generates docblock stuff

$argv = array_slice($argv, 1);

if (!isset($argv[0]))
    die('Expected file name');

$file = $argv[0];

$content = file_get_contents($file);

$content = preg_replace_callback('/(\/\/(.*?)\n(\s*)(?:public|private|protected)?\s*function\s+.*?\((.*?)\)(?:\s*:\s*(.*?))?\s*{[\s\S]*?})/m', function($matches) {

    $tab = $matches[3];
    $docblock = '/**' . PHP_EOL;
    $docblock .= $tab . ' * ' . trim($matches[2]) . PHP_EOL;
    $docblock .= $tab . ' *';
    $docblock .= PHP_EOL;
    
    if ($matches[4])
        foreach (explode(',', $matches[4]) as $param)
            $docblock .= $tab . ' * @param ' . (strpos($param, ' ') ? '' : 'mixed ') . trim($param) . PHP_EOL;
    
    $docblock .= $tab . ' * @return '. trim($matches[5] ?? 'void') . PHP_EOL .
                 $tab . ' */';

    return preg_replace('/\/\/.+/', $docblock, $matches[0]);
}, $content);

$content = preg_replace_callback('/\/\/(.+)\n(\s*)(?:public|private|protected)\s*(.*?)\s*\$.*?;/', function($matches) {
    $ta = $matches[2];
    $docblock = '/**' . PHP_EOL;
    $docblock .= $tab . ' * ' . trim($matches[1]) . PHP_EOL;
    $docblock .= $tab . ' *' . PHP_EOL;
    $docblock .= $tab . ' * @var ' . ($matches[3] == '' ? 'mixed' : '') . PHP_EOL;
    $docblock .= $tab . ' */';

    return preg_replace('/\/\/.+/', $docblock, $matches[0]);
}, $content);

file_put_contents($file, $content);b
