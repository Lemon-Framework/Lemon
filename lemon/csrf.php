<?php
class CSRF
{
    static function setToken()
    {
        if (!isset($_SESSION["csrf_token"]))
        {
            $token = uniqid();
            $token = hash("sha256", $token);
            $_SESSION["csrf_token"] = $token;
        }
    }

    static function check()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST")    
        {
            if (isset($_POST["csrf_token"]) && isset($_SESSION["csrf_token"]))
            {
                if ($_POST["csrf_token"] != $_SESSION["csrf_token"])
                {
                    raise(400);
                    exit();
                } 
            }
            else
            {
                raise(400);
                exit();
            }
        }
    }
    static function getToken()
    {
        if (isset($_SESSION["csrf_token"])) 
        {       
            return $_SESSION["csrf_token"];
        }
        else
        {
            raise(400);
            exit();
        }
    }
}
?>
