<?php

namespace Lemon\Sessions;

/*
 *
 * Class to setting session
 *
 * Every method must be on the start of app
 *
 * */
class Session
{
    /*
     *
     * Sets name of session
     *
     * @param string $name
     *
     * */
    public static function setName($name)
    {
        session_name($name);
    }

    // Starts session
    public static function start()
    {
        session_start();
    }
}
