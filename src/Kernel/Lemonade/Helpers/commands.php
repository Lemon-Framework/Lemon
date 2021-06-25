<?php

require "info.php";
require __DIR__."/../Server/server.php";
require __DIR__."/../Builders/builder.php";

/*
 *
 * List of all commands
 *
 * */
$commands = [
    "-h" => "help",
    "-i" => "info",
    "-v" => "version",
    "serve" => "serve",
    "build" => "build",
    "routes" => "routes"

];
?>
