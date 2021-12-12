<?php

// Importing text format for better colors
require "helpers.php";

// List of all commands
const HELP = "    
    -h Shows this help
    -i Shows info about lemon
    -v Shows version of Lemon

    serve Starts development server
    build type:project - Builds starter project
              :license - Builds license
    repl Starts lemon interactive shell

";

const VERSION = "2.6.7";

// Shows help
function help()
{
    echo textFormat("\n\u{1F34B} Lemon help\n", "33");
    echo HELP;
 
}

// Shows info about project
function info()
{
    $version = VERSION;

    echo textFormat("\n\u{1F34B} Lemon info\n", "33");
    echo "\nLemon is simple micro framework that provides routing, etc.\n";
    echo "Developed by TEN MAJKL -> Version {$version}\n\n";
}

// Show version
function version()
{
    $version = VERSION;

    echo textFormat("\n\u{1F34B} Lemon version\n", "33");
    echo "\n->{$version}\n";
}


