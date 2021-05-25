<?php
require __DIR__ . "/../../loader.php";
require __DIR__ . "/../../../routing/routes.php";

use Lemon\Routing\Route;
use Lemon\Kernel;

Lemon\Kernel\loader();

function routes()
{
    $routes = Route::getRoutes();
    echo "Routes\n------\n";
    foreach ($routes as $path=>[$action, $methods])
    {
        $methods = join(' ', $methods);
        echo $path == "" ? "/=>{$methods}\n" : $path."=>{$methods}\n";
    }
    echo "\n";
}

?>

