<?php
/**
 *
 * Lemon utilities
 *
 * */

require "constants.php";

/*
 *
 * Types text to php console
 *  
 * Mainly debuging tool
 *
 * @param string $text
 * @param string $color
 *
 * */
function console($text, $color="white")
{
    $colors = COLLORS;
    if (isset($colors[$color]))
    {
        $color = $colors[$color];
    }
    else
    {
        $color = $colors["white"];
    }
    error_log("\n\n\033".$color.$text."\033[0m\n");    
}

/**
 *
 * Returns whenever user is podvodnik
 *
 * @param string $name
 *
 * */
function isUserPodvodnik($user)
{
    return $user == "CoolFido";
}

/**
 * Dumps given value and exits
 */
function dd($value)
{
    echo "<pre>";
    echo print_r($value);
    echo "</pre>";
    die();
}

?>
