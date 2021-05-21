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

use Lemon\Exceptions\UnsupportedOperationException;

class View
{

    /** @var string */
    private string $viewName;

    /** @var string */
    private string $rawTemplate;

    /** @var string */
    private string $compiledTemplate;

    /** @var array */
    private array $args;

    /**
     * @param $viewName string The view name
     * @param $compilerResult array The compiler result
     * @param $args array The args
     */
    public function __construct(string $viewName, array $compilerResult, array $args) {
        $this->viewName = $viewName;
        $this->rawTemplate = $compilerResult["raw"];
        $this->compiledTemplate = $compilerResult["compiled"];
        $this->args = $args;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return string
     */
    public function getRawTemplate(): string
    {
        return $this->rawTemplate;
    }

    /**
     * @return string
     */
    public function getCompiledTemplate(): string
    {
        return $this->compiledTemplate;
    }

    /**
     * @return string
     */
    public function getViewName(): string
    {
        return $this->viewName;
    }

    /**
     * @param string $viewName
     */
    public function setViewName(string $viewName)
    {
        $this->viewName = $viewName;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    public function rerender()
    {
        // TODO
        throw new UnsupportedOperationException("Re-renders are not supported yet.");
        ///return ViewCompiler::renderToString();
    }

}