<?php
/**
 *
 * Lemon utilities
 *
 * */

include "constants.php";

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
    global $colors;
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
 * Redirects user to given url
 *
 * @param string $url
 *
 * */
function redirect($path)
{
    header("Location:".$path);
}

/**
 *
 * Converts array to json
 *
 *
 * @param array $content
 *
 * */
function jsonify($content)
{
    echo json_encode($content);
    header("Content-type:application/json");
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
 *
 * DumpDie
 * Dumps given value
 *
 * Similar to Laravel dd function
 *
 *
 * */
function dd($value)
{
    echo "<pre>";
    echo print_r($value);
    echo "</pre>";
    die();
}

?>
