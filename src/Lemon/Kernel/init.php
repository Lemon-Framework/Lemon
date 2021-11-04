<?php

use Lemon\Http\Response;
use Lemon\Views\ViewCompiler;

if (!defined("LEMON_MODE"))
    define("LEMON_MODE", "web");

set_exception_handler(function($ex) {
    if (!defined("DEBUG"))
        return (new Response("", 500))->terminate();
    $contents = [];
    $ex_trace = $ex->getTrace();
        $traces = array_merge([["file" => $ex->getFile(), "line" => $ex->getLine()]], $ex_trace);
    foreach ($traces as $trace)
    {
        if (!isset($trace["file"]))
            continue;
        $fname = trim($trace["file"], getenv('HOME'));
        $vendor = strpos($fname, "vendor");

        array_push($contents, [$fname, file_get_contents($trace["file"]), $trace["line"], $vendor]);

    }
    $contents = json_encode($contents);
    $view_compiler = new ViewCompiler(
        "error",
        file_get_contents(__DIR__."/views/error.lemon.php"),
        ["ex"=>$ex, "contents" => $contents]
    );
    $view = $view_compiler->compile();
    echo $view->resolve();
});
