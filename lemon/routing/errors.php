<?php

/*
 *
 * Lemon errors file
 *
 * */

require __DIR__ . "/../utils/constants.php";

/*
 *
 * Throws specified status code with message.
 *
 * @param int $status_code
 *
 * */
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
        echo "<h3>Lemon</h3>";
    }
    
    http_response_code($error);
}

?>
