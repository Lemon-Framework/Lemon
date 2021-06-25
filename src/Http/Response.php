<?php

namespace Lemon\Http;

require __DIR__."/../utils/page.php";

/**
 *
 * Lemon Response class
 *
 * Mainly for stats codes
 *
 * */
class Response
{

    // List of all handlers
    static $handlers = [];

    /**
     *
     * Raises status code
     *
     * It also calls status handlers
     *
     * @param int $code
     *
     * */
    static function raise($code)
    {
        if (isset(self::$handlers[$code]))
            self::$handlers[$code]();
        else
            status_page($code);

        http_response_code($code);
        exit(); 
    }

    /**
     *
     * Sets status code handler
     *
     * @param int $code
     * @param Closure|String $callback|$function_name
     *
     * */
    static function handle($code, $action)
    {
        self::$handlers[$code] = $action;
    }
    
    /**
     *
     * Redirects you to specified url
     *
     * @param String $url
     *
     * */
    static function redirect($url)
    {
        header("Location:".$url, true, 301);
    }

}

?>
