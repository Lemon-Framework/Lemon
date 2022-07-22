<?php

/** @var \Lemon\Routing\Router $router */

$router->get('/', function() {
    return 'hi';
});

$router->post('/foo', function() {
    return 'foo';
});
