<?php

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

/*
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

/*
 *
 * Adds json to page
 *
 * Tool for building APIs
 *
 * @param array $content
 *
 * */
function jsonify($content)
{
    echo json_encode($content);
    header("Content-type:application/json");
}

/*
 *
 * Returns if user is podvodnik
 *
 * @param string $name
 *
 * */
function isUserPodvodnik($user)
{
    return $user == "CoolFido";
}

?>
