<?php
/*
 
    Throws specific error to user

 */
function raise(int $error)
{
    global $errors;
    global $handlers;
    if (isset($handlers[$error]))
    {
        $handlers[$error]();
    }
    else
    {
        echo "<h1> {$error} - {$errors[$error]} </h1> <hr>";
        echo "<h3>Brush</h3>";
    }
    
    http_response_code($error);
}

?>
