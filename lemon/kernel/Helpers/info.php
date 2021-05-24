<?php

// Importing text format for better colors
require "helpers.php";

// List of all commands
$help = "    
    -h Shows this help
    -i Shows info about lemon
    -v Shows version of Lemon

    serve Starts development server
    build type:project - Builds starter project
              :license - Builds license

";

$version = "2.0.0";

// Shows help
function help()
{
    global $help;

    echo textFormat("\n\u{1F34B} Lemon help\n", "33");
    echo $help;
 
}

// Shows info about project
function info()
{
    global $version;

    echo textFormat("\n\u{1F34B} Lemon info\n", "33");
    echo "\nLemon is simple micro framework that provides routing, etc.\n";
    echo "Developed by TEN MAJKL -> Version {$version}\n\n";
}

// Show version
function version()
{
    global $version;

    echo textFormat("\n\u{1F34B} Lemon version\n", "33");
    echo "\n->{$version}\n";
}

?>
