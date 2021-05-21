<?php

class Builder
{
    private $arguments;

    private $arg_list = [
        "type",    
    ];

    private $types = [
        "project"
    ];

    private $dirs = [
        "public",
        "views",
        "routes",
        "controllers",
    ];

    private $files = [
        "public/index.php" => "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/index.php",
        "routes/web.php" => "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/web_routes.php"
    ];

    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }
    
    private function parse($arguments)
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
    public function execute()
    {
        $arguments = $this->parse($this->arguments);
        if(in_array($arguments["type"], $this->types))
        {
            $action = $arguments["type"];
            $this->$action($arguments);
        }

    }

    private function project()
    {
        echo textFormat("\nBuilding project...\n\n", "33");
        foreach ($this->dirs as $dir)
        {
            if(!file_exists($dir))
            {
                echo textFormat("Building {$dir}...\n", "33");
                mkdir($dir);
            }
        } 
        foreach ($this->files as $file => $link)
        {
            echo textFormat("Building {$file}...\n", "33");
            $file = fopen($file, "w");
            $content = file_get_contents($link);
            fwrite($file, $content);
        }
        echo textFormat("\nDone!\n\n", "33");
    }
}

function build($arguments)
{
    $builder = new Builder($arguments);

    $builder->execute();
}

?>
