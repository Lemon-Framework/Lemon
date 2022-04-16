<?php

declare(strict_types=1);

if (!function_exists('status_page')) {
    function status_page($code): void
    {
        $errors = ERRORS;
        $message = $errors[$code];
        echo "<h1>{$code}-{$message}</h1>";
        echo '<hr>';
        echo 'Lemon';
    }
}
