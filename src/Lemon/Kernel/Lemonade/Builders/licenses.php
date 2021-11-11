<?php
require "templates.php";

/*
 *
 * Class for building licenses using interactive command
 *
 * */
class LicenseBuilder
{
    // License parameters
    private $parameters = [
        "license" => "",
        "author" => "",
        "date" => ""
    ];

    /*
     *
     * Starts interactive command
     *
     * */
    public function __construct()
    {
        echo textFormat("License Builder\n\n", "33");
        $this->licenseType();
    }

    /*
     *
     * Assigns license type
     *
     * */
    private function licenseType()
    {
        $licenses = LICENSES;
        $type = readline("Type license you want to build: ");

        if (isset($licenses[$type]))
        {
            $this->parameters["license"] = $type;
            $this->licenseAuthor();
            $this->parameters["date"] = date("Y");
            return;
        }

        if ($type == "custom")
        {
            $license = readline("Type your license content: ");
            $this->parameters["license"] = $license;
            return;
        }

        echo textFormat("This license is not available!\n", "31");
        $this->licenseType();

    }

    /*
     *
     * Assigns license author
     *
     * */
    private function licenseAuthor()
    {
       $name = readline("Type author name: ");

        if ($name != null)
        {
            $this->parameters["author"] = $name;
            return;
        }
        
        echo textFormat("Name must be specified!\n", "31");
        $this->licenseAuthor();
    }

    /*
     *
     * Builds whole license file
     *
     * */
    public function buildLicense()
    {
        $licenses = LICENSES;
        $file = fopen("LICENSE.md", "w");
        $license = $this->parameters["license"];
        
        if (!in_array($license, $licenses))
        {
            echo textFormat("Building license...\n", "33");
            fwrite($file, $license);
            echo textFormat("Done!\n", "33");
            return;
        }
        $content = file_get_contents($licenses[$license]);
        $content = str_replace("<copyright holders>", $this->parameters["author"], $content);
        $content = str_replace("<date>", $this->parameters["date"], $content);
        echo textFormat("Building license...\n", "33");
        fwrite($file, $content);
        echo textFormat("Done!\n\n", "33");
    }

}


