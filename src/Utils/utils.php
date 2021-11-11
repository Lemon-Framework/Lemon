<?php
/**
 *
 * Lemon utilities
 *
 * */

/*
 *
 * Types text to php console
 *  
 * Mainly debugging tool
 *
 * @param string $text
 * @param string $color
 *
 * */
function console($text, $color="white")
{
    $colors = COLORS;
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
 * @param string $user
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


