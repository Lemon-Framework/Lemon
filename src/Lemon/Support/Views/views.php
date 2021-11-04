<?php
/*
 *
 * Lemon template system
 *
 * */

use Lemon\Views\ViewCompiler;
use Lemon\Views\View;
use Lemon\Exceptions\ViewException;

function view($view_name, $arguments = [], $folder="")
{
    $directory = $folder == "" ? View::$directory : $folder;
    $format = View::$format;
    $name = preg_replace("/\\./", DIRECTORY_SEPARATOR, $view_name);
    $view_path = $directory . DIRECTORY_SEPARATOR . $name . $format;

    if (!file_exists($view_path) || !is_readable($view_path))
        throw new ViewException("View $view_name does not exist or is not readable!");

    $view_raw = file_get_contents($view_path);
    
    $compiler = new ViewCompiler($name, $view_raw, $arguments); 

    return $compiler->compile();
}

?>
