<?php

/*

    Executes template
 
 */
function view($file, $options = [])
{
    if (file_exists("./views/".$file))
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
    else
    {
        raise(500);
        console("ViewError: View ".$file." not found!", "red");
    }
}

?>
