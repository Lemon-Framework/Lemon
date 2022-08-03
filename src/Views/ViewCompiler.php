<?php

namespace Lemon\Views;

use Lemon\Views\View;
use Lemon\Views\Tag;
use Lemon\Exceptions\ViewException;
use Lemon\Sessions\Csrf;

class ViewCompiler
{
    /** Templates directory */
    public static $directory;

    /** File suffix */
    public static $format = ".lemon.php";

    /**
     * Returns pre-defined tags
     *
     * @return Array
     *
     */
    public static function getTags()
    {
      return [
              new Tag(["<?= htmlentities(", "{{"], [") ?>", "}}"]),
              new Tag(["<?= ", "{!"], [" ?>", "!}"]),
              new Tag(["<?php ", "{%"], [" ?>", "%}"])
      ];
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

    /**
     * Compiles defined mapping
     *
     * @param string $content
     *
     * @return String
     *
     */
    public static function map(String $content)
    {
        $mapping = [
            ["/@csrf/", '<input type="hidden" value="'. Csrf::getToken().'" name="csrf_token">']
        ];
        foreach ($mapping as $map)
            $content = preg_replace($map[0], $map[1], $content);

        return $content;
    }

    /**
     * Compiles all defined tags
     *
     * @param String $content
     *
     * @return String
     *
     */
    public static function compileTags(String $content)
    {
        $tags = self::getTags();

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
        if (preg_match("/<\?(php|=)/", $view_raw, $matches))
            throw new ViewException("Unexpected <?{$matches[1]} in view {$view_name}");
        $view_tags = self::compileTags($view_raw);

        $view_compiled = "?>" . self::map($view_tags);

        return new View($view_name,
                        ["raw" => $view_raw,
                        "compiled" => $view_compiled],
                        $arguments);
    }
}


