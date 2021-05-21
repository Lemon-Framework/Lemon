<?php declare(strict_types=1);

namespace Lemon\Templating;

class Tag
{

    /** @var string[] */
    private $openingTag;

    /** @var string[] */
    private $closingTag;

    public function __construct($openingTag, $closingTag)
    {
        $this->openingTag = $openingTag;
        $this->closing = $closingTag;
    }

    public function compileTag($content) {
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