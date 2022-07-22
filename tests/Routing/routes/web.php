<?php

declare(strict_types=1);

/** @var \Lemon\Routing\Router $router */
$router->get('/', function () {
    return 'hi';
});

$router->post('/foo', function () {
    return 'foo';
});
