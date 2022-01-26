<?php

use Lemon\Http\Response;

test("getParamTypes returns correct type", function() {
    $param_types = getParamTypes(function(Response $foo){});
    expect($param_types)->toBe(["Lemon\Http\Response"]); 
});
