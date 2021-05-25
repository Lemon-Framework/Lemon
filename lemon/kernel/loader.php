<?php

namespace Lemon\Kernel;

/*
 *
 * Loads all routes from routes folder
 *
 * Built for index in public folder
 *
 *
 * */
function loader()
{
    $routes_dir = __DIR__."/../../routes/";

    if (is_dir($routes_dir))
    {
        foreach (scandir($routes_dir) as $dir)
        {
            if (str_ends_with($dir, ".php"))
                require $routes_dir.$dir;
        }
    }
}

?>
