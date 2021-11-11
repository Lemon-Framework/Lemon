<?php

test("getParamTypes returns correct type", function() {
    $param_types = getParamTypes(function(String $foo){});
    expect($param_types)->toBe(["string"]); 
});
