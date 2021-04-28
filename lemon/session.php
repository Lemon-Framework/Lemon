<?php
class Session
{
    static function setName($name)
    {
        session_name($name);
    }

    static function start()
    {
        session_start();
    }
}

?>
