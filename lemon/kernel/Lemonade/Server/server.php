<?php

/*
 *
 * Running local production server
 *
 * */
class Server
{   
    private $arguments;
    private $arg_list = [
        "port",
        "host"
    
    ];
    
    /*
     *
     * Takes arguments
     *
     * */
    public function __construct($arguments)
    {
        $this->arguments = $arguments;

    }

    /*
     *
     * Parses arguments to associative array
     *
     * */
    private function parse()
    {
        $parsed = [];
        foreach($this->arguments as $argument)
        {
            if(str_contains($argument, ":"))
            {
                $argument = explode(':', $argument);
                $parsed[$argument[0]] = $argument[1];
            }
        }

        return $parsed;
    }

    /*
     *
     * Builds serving command
     *
     * */
    private function build()
    {
        $arguments = $this->parse();
        
        $address = isset($arguments['host']) ? $arguments['host'] : "localhost";
        $port = isset($arguments['port']) ? $arguments['port'] : "8000";
        $dir = __DIR__ . "/../../../../public/index.php";

        $command = "php -S {$address}:{$port} {$dir}";
        return $command;
    }

    /*
     *
     * Runs whole server
     *     
     * */
    public function run()
    {
        if (is_file("public/index.php"))
        {
            echo textFormat("\n\u{1F34B} Lemon development server started!\n\n", "33");
            $command = $this->build();

            exec($command);
        }
        else
            echo textFormat("Folder *public* is required to run server!\n", 31);
    }

}

/*
 *
 * Function for command registration
 *
 * */
function serve($arguments)
{
    $server = new Server($arguments);

    $server->run();
}

?>
