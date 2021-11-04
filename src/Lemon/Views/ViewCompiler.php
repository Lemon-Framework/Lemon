<?php

namespace Lemon\Views;

use Lemon\Views\View;
use Lemon\Views\Tag;
use Lemon\Sessions\Csrf;

class ViewCompiler
{
    public $view_name;
    public $view_raw;
    public $arguments;
    public $tags;

    public function __construct(String $view_name, String $view_raw, Array $arguments)
    {
        $this->view_name = $view_name;
        $this->view_raw = $view_raw;
        $this->arguments = $arguments;
        $this->tags = [
              new Tag(["<?= htmlentities(", "{{"], [") ?>", "}}"]),
              new Tag(["<?= ", "{!"], [" ?>", "!}"]),
              new Tag(["<?php ", "{%"], [" ?>", "%}"])
        ];
    }

    /**
     * Compiles defined mapping
     *
     * @param string $content
     *
     * @return String
     *
     */
    public function map(String $content)
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
    public function compileTags(String $content)
    {
        $tags = $this->tags;

        foreach ($tags as $tag)
        {
            $o = $tag->open_tag[1];
            $c = $tag->close_tag[1];

            $content = preg_replace_callback("/$o([^\{\}]+)$c/", function($matches) use($tag) {
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
    public function compile()
    {
        $view_tags = $this->compileTags($this->view_raw);

        $view_compiled = "?>" . $this->map($view_tags);

        return new View($this->view_name,
                        ["raw" => $this->view_raw,
                        "compiled" => $view_compiled],
                        $this->arguments
        );
    }
}

?>
