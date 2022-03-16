<?php

declare(strict_types=1);

require 'info.php';

require __DIR__.'/../Server/server.php';

require __DIR__.'/../Builders/builder.php';

require __DIR__.'/../Repl/repl.php';

// List of all commands
const COMMANDS = [
    '-h' => 'help',
    '-i' => 'info',
    '-v' => 'version',
    'serve' => 'serve',
    'build' => 'build',
    'routes' => 'routes',
    'repl' => 'repl',
];
