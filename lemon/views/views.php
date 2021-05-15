<?php

/*
 *
 * Views lemon template
 *
 * @param string $filename
 * @param array $options
 *
 * */
function view($file, $options = [])
{
    $file = "../../../views".$file.".lemon.php";
    if (file_exists($file))
    {
        $safe = [];
        foreach ($options as $option => $value)
        {
            $safe[$option] = str_replace('<', '&lt', $value);
        }
        extract($safe);
        $file = file_get_contents($file, "r");
        $file = str_replace('{{', '<?=', $file);
        $file = str_replace('}}', '?>', $file);
        $file = str_replace('{%', '<?php', $file);
        $file = str_replace('%}', '?>', $file);
        $file = str_replace('@csrf', '<input type="hidden" value="'. CSRF::getToken().'" name="csrf_token">', $file);

        eval("?>".$file);
    }
    else
    {
        raise(500);
        console("ViewError: View ".$file." not found!", "red");
    }
}

?>
