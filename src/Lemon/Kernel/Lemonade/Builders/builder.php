<?php

require "licenses.php";

/*
 *
 * Class for build command
 *
 * Can build project or license
 *
 * */
class Builder
{
    // List of all arguments provided by user
    private $arguments;

    /*
     *
     * Assigns arguments
     *
     * @param array $arguments;
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
     * @param array $arguments
     *
     * */
    private function parse($arguments)
    {
        $parsed = [];
        foreach($this->arguments as $argument)
        {
            if(strpos($argument, ":"))
            {
                $argument = explode(':', $argument);
                $parsed[$argument[0]] = $argument[1];
            }
        }

        return $parsed;
    }

    /*
     *
     * Executes specific builder
     *
     * Executing is provided by argument `type`
     *
     * */
    public function execute()
    {
        $types = TYPES;
        $arguments = $this->parse($this->arguments);
        if (!isset($arguments["type"]))
        {
            echo textFormat("Type argument is missing!\n", 31);
            return;
        }
        if(in_array($arguments["type"], $types))
        {
            $action = $arguments["type"];
            $this->$action($arguments);
            return;
        }
        echo textFormat("Type not found!\n", 31);
    }

    /*
     *
     * Builds starting project
     *
     * */
    private function project()
    {
        $dirs = DIRS;
        $files = FILES;

        echo textFormat("\nBuilding project...\n\n", "33");
        foreach ($dirs as $dir)
        {
            if(!file_exists($dir))
            {
                echo textFormat("Building {$dir}...\n", "33");
                mkdir($dir);
            }
        }
        foreach ($files as $file => $link)
        {
            echo textFormat("Building {$file}...\n", "33");
            $file = fopen($file, "w");
            $content = file_get_contents($link);
            fwrite($file, $content);
        }
        echo textFormat("\nDone!\n\n", "33");
    }

    /*
     *
     * Provider of generating license
     *
     * Generator is in LicenseBuilder class
     *
     * */
    private function license()
    {
        $licenses = new LicenseBuilder();

        $licenses->buildLicense();



    }
}

/*
 *
 * Function for creating Builder instance
 *
 * @param array $arguments
 *
 * */
function build($arguments)
{
    $builder = new Builder($arguments);

    $builder->execute();
}

?>
