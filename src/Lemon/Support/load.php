<?php

use Lemon\Support\Utils\Str;

if (!function_exists("load"))
{
    function load($file)
    {
        $file_content = file_get_contents($file);
        $result = $file_content;
        $result = preg_replace_callback("/(\"[^\"]*\")|('[^']*')/", function($matches) {
            return "Str::from(" . $matches[0] . ")";
        }, $result);
        echo $result;
    }
}
