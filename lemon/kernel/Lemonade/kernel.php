<?php

namespace Lemon\Kernel;

require "Helpers/commands.php";

/*
 * 
 * Lemonade Kernel
 * 
 * Producing system for Lemon
 *
 * */
class Kernel
{
    private $command;

    public function __construct($command)
    {
        $this->command = $command;
    }
    
    public function execute()
    {
        $command = isset($this->command[1]) ?  $this->command[1] : "";
        $arguments = array_slice($this->command, 2);
        global $commands;
        isset($commands[$command]) ? $commands[$command]($arguments) : $commands["-h"]();
        
    }    

}

?>
