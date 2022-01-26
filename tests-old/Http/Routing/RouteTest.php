<?php

use Lemon\Http\Request;
use Lemon\Http\Routing\Dispatcher;
use Lemon\Http\Routing\Route;

test("basic routing", function() {
    $route = new Route("/foo", ["GET"], function(){
        return "cs";
    });
    $d = new Dispatcher([$route], new Request(["uri" => "/foo", "method" => "GET"]));
    expect($d->run()->body)->toBe("cs");
});


