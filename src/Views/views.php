<?php
/*
 *
 * Lemon template system
 *
 * */
use Lemon\Sessions\CSRF;
use Lemon\Http\Response;

/*
 * Views lemon template
 *
 * @param string $filename
 * @param array $options
 *
 * */
function view($view, $options = [])
{
    $exec_file = debug_backtrace();
    $exec_dir = pathinfo($exec_file)["dirname"];
    $file = $exec_dir."/../views/".$view.".lemon.php";
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
        if (str_contains($file, "@csrf"))
        {
            $file = str_replace('@csrf', '<input type="hidden" value="'. CSRF::getToken().'" name="csrf_token">', $file);
        }

        eval("?>".$file);
    }
    else
    {
        Response::raise(500);
        console("ViewError: View ".$view." not found!", "red");
    }
}

?>
