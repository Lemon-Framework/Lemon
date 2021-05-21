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

class Tag
{

    /** @var string[] */
    private array $openingTag;

    /** @var string[] */
    private array $closingTag;

    public function __construct($openingTag, $closingTag)
    {
        $this->openingTag = $openingTag;
        $this->closingTag = $closingTag;
    }

    public function compileTag($content): string
    {
        return $this->openingTag[1] . $content . $this->closing[1];
    }

    /**
     * @return string[]
     */
    public function getOpeningTagCombination(): array
    {
        return $this->openingTag;
    }

    /**
     * @return string[]
     */
    public function getClosingTagCombination(): array
    {
        return $this->closingTag;
    }

    /**
     * @return string[]
     */
    public function getOpeningTag(): array
    {
        return $this->getOpeningTagCombination()[0];
    }

    /**
     * @return string[]
     */
    public function getClosingTag(): array
    {
        return $this->getClosingTagCombination()[0];
    }

    /**
     * @return string[]
     */
    public function getCompiledOpeningTag(): array
    {
        return $this->getOpeningTagCombination()[1];
    }

    /**
     * @return string[]
     */
    public function getCompiledClosingTag(): array
    {
        return $this->getClosingTagCombination()[0];
    }

}
