<?php

function status_page($code)
{
    global $errors;
    $message = $errors[$code];
    echo "<h1>{$code}-{$message}</h1>";
    echo "<hr>";
    echo "Lemon";
}

?>
