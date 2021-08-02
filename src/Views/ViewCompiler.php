<?php

namespace Lemon\Views;

use Lemon\Views\View;
use Lemon\Views\Tag;
use Lemon\Exceptions\ViewException;

class ViewCompiler
{
    /** Templates directory */
    public static $directory;

    /** File suffix */
    public static $format = ".lemon.php";

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

    /**
     * Creates all pre-defined tags
     *
     * @return Array
     *
     */
    public static function tags()
    {
        return [
            new Tag(["<?= htmlentities(", "{{"], [") ?>", "}}"]),
            new Tag(["<?= ", "{!"], [" ?>", "!}"]),
            new Tag(["<?php ", "{%"], [" ?>", "%}"])
        ];
    }

    /**
     * Compiles all pre-defined tags
     *
     * @param String $content
     *
     * @return String
     *
     */
    public static function compileTags(String $content)
    {
        $tags = self::tags();

        foreach ($tags as $tag)
        {
            $o = $tag->open_tag[1];
            $c = $tag->close_tag[1];

            $content = preg_replace_callback("/$o(.+)$c/", function($matches) use($tag) {
                return $tag->compile($matches);
            }, $content);
        }

        return $content;
    }

    /**
     * Compiles template to View class
     *
     * @param String $view_name
     * @param Array $arguments
     *
     * @return View
     *
     */
    public static function compile(String $view_name, Array $arguments)
    {
        $name = preg_replace("/\\./", DIRECTORY_SEPARATOR, $view_name);
        $view_path = self::$directory . DIRECTORY_SEPARATOR . $name . self::$format;

        if (!file_exists($view_path) || !is_readable($view_path))
            throw new ViewException("View $view_name does not exist or is not readable!");

        $view_raw = file_get_contents($view_path);

        $view_compiled = "?>" . self::compileTags($view_raw);

        return new View($view_name,
                        ["raw" => $view_raw,
                        "compiled" => $view_compiled],
                        $arguments);
    }
}

?>
