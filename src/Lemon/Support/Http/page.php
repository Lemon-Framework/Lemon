<?php

if (!function_exists("status_page"))
{
    function status_page($code)
    {
        $errors = ERRORS;
        $message = $errors[$code];
        echo "<h1>{$code}-{$message}</h1>";
        echo "<hr>";
        echo "Lemon";
    }
}
