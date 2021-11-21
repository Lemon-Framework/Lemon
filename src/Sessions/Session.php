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
    static function setName($name)
    {
        session_name($name);
    }

    /*
     *
     * Starts session
     *
     * */
    static function start()
    {
        session_start();
    }
}


