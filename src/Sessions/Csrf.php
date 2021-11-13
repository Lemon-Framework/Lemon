<?php
namespace Lemon\Sessions;

use Lemon\Http\Response;

/**
 * CSRF preventing class
 */
class Csrf
{

    /**
     * Creates new CSRF token and saves it into user's session
     */
    static function setToken()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET")
        {
            $token = uniqid();
            $token = hash("sha256", $token);
            $_SESSION["csrf_token"] = $token;
        }

    }

    /**
     * Validates CSRF token by comparing the one from POST input and session
     */
    static function check()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (isset($_POST["csrf_token"]) && isset($_SESSION["csrf_token"]))
            {
                if ($_POST["csrf_token"] !== $_SESSION["csrf_token"])
                {
                    Response::raise(400);
                    exit();
                }
            }
            else
            {
                Response::raise(400);
                exit();
            }
        }
    }

    /**
     * Returns CSRF token from session
     *
     * @return String
     */
    static function getToken()
    {
        return $_SESSION["csrf_token"] ?? "";
    }
}

