<?php

$start = microtime(true);

define("DEBUG", true);

require __DIR__ . "/../vendor/autoload.php";

Route::get("/foo", function(){
    return 0/0;
});

Route::get("/", function(){
    return "baz";
});

Route::execute();

$end = microtime(true);

echo $end - $start;
