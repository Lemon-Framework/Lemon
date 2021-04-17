<?php

/*
 Make view
 
 */

function view($file, $options = [])
{
    $safe_options = [];
    foreach ($options as $key => $option)
    {
        $option = str_replace("<", "&lt", $option);
        $safe_options[$key] = $option;
    }
    extract($safe_options);
    require "./views/".$file;
}

?>
