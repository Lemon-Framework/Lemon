<?php

/*

    Executes template
 
 */
function view($file, $options = [])
{
    $file = "./views/".$file.".lemon.php";
    if (file_exists($file))
    {
        extract($options);
        $file = file_get_contents($file, "r");
        $file = str_replace('<', '&lt', $file);
        $file = str_replace('{{', '<?=', $file);
        $file = str_replace('}}', '?>', $file);
        $file = str_replace('{%', '<?php', $file);
        $file = str_replace('%}', '?>', $file);
        eval("?>".$file);
    }
    else
    {
        raise(500);
        console("ViewError: View ".$file." not found!", "red");
    }
}

?>
