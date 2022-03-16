<?php

declare(strict_types=1);

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
    public static function setName($name): void
    {
        session_name($name);
    }

    // Starts session
    public static function start(): void
    {
        session_start();
    }
}
