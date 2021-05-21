<?php declare(strict_types=1);

/*
 * Lemon - dead simple PHP framework
 * Copyright (c) 2021 TENMAJKL and Contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Lemon\Templating;

use Lemon\Exceptions\ViewException;

class ViewCompiler {

    /** @var string */
    const VIEW_FILE_EXT = ".lemon.php";

    /** @var string */
    private string $viewsPath;

    /** @var Tag[] */
    private array $tags;

    /**
     * The compiler for Lemon view templates.
     *
     * @param $viewsPath string
     */
    public function __construct(string $viewsPath) {
        $this->viewsPath = $viewsPath;

        // Register all tags.
        $this->tags = [
            new Tag(["{!", "<?="], ["!}", ">"]),
            new Tag(["{{", "<?=     htmlspecialchars("], [")}}", "?>"]),
            new Tag(["{%", "<?php"], ["%}", "?>"])
        ];
    }

    /**
     * Resolves the view source code.
     *
     * @param $viewName string
     * @return string
     * @throws ViewException
     */
    private function resolveView(string $viewName): string
    {
        $viewPath = $this->viewsPath . DIRECTORY_SEPARATOR . $viewName . self::VIEW_FILE_EXT;

        if (
            !file_exists($viewPath) ||
            !is_readable($viewPath) ||
            !$file = file_get_contents($viewPath)
        ) {
            throw new ViewException("View $viewName does not exist or is not readable.");
        }

        return $file;
    }

    /**
     * Compiles the template. Returns an array containing the template source and
     *
     * @param $viewName string
     * @param $args array
     * @return string[]
     * @throws ViewException
     */
    private function compile(string $viewName, array $args): array
    {
        $rawView = $this->resolveView($viewName);

        extract($args);

        $compiledView = $this->compileTags($rawView);
        $compiledView = $this->compileCsrfMixin($compiledView);

        return [
            "raw" => $rawView,
            "compiled" => $compiledView
        ];
    }

    /**
     * Matches all tags and compiles them.
     *
     * @param $view string
     * @return string
     */
    private function compileTags(string $view): string {
        foreach ($this->tags as $tag) {
            $o = $tag->getCompiledOpeningTag();
            $c = $tag->getCompiledClosingTag();

            $view = preg_replace_callback("/(?<!@)$o(.*?)$c/", function ($matches) use ($tag) {
                return $tag->compileTag($matches[0]);
            }, $view);
        }

        return $view;
    }

    /**
     * Replaces @csrf with an input tag containing the CSRF token.
     * TODO: ability to escape by using "@@csrf"
     *
     * @param string $view
     * @return string
     */
    private function compileCsrfMixin(string $view): string
    {
        return str_replace('@csrf', '<input type="hidden" value="{{ csrf() }}" name="csrf_token">', $view);
    }

    /**
     * Compiles a view and creates a new View instance.
     *
     * @param $viewName string
     * @param $args array
     * @return View
     * @throws ViewException
     */
    public function makeView(string $viewName, array $args): View
    {
        return new View($viewName, $this->compile($viewName, $args), $args);
    }

}
