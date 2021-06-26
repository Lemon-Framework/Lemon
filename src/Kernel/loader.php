<?php
/*
 *
 * Loads all routes from routes folder
 *
 * Built for index in public folder
 *
 *
 * */
function loader($dir)
{
    $routes_dir = $dir."/../routes/";

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
