<?php
/**
 * Templating system is inspired by the one that Mia made
 * https://github.com/VottusCode
 *
 */

namespace Lemon\Views;

class View
{
    /** Directory containing all views */
    public static $directory = "";

    /** File suffix */
    public static $format = ".lemon.php";

    /** Template name */
    public $name;

    /** Raw template content */
    public $raw_template;

    /** Compiled template content*/
    public $compiled_template;

    /** Evaled template, that returns raw html */
    public $resolved_template;

    /** Template arguments */
    public $arguments;

    /**
     * Creates new Lemon Template instance
     *
     * @param String $name
     * @param Array $result
     * @param Array $args
     *
     */
    public function __construct(String $name, Array $result, Array $args = [])
    {
        $this->name = $name;
        $this->raw_template = $result["raw"];
        $this->compiled_template = $result["compiled"];
        $this->arguments = $args;
    }

    public function resolve()
    {
        ob_start();
        extract($this->arguments);
        eval($this->compiled_template);
        return ob_get_clean();
    }

    /**
     * Sets templates directory
     *
     * @param String $path
     *
     */
    public static function setDirectory(String $path)
    {
        self::$directory = $path;
    }

}



