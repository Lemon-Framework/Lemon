<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Exceptions\ParserException;
use Lemon\Templating\Juice\Parser;
use Lemon\Templating\Juice\Token as T;
use Lemon\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ParserTest extends TestCase
{
    public function testParsingTags()
    {
        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar']),
            new T(T::TAG_END, 'foreach'),
        ]);

        $this->assertSame('<?php foreach ($foo as $bar): ?><?php endforeach ?>', $p->parse());

        $p = $this->getParser([
            new T(T::TAG, ['if', '$foo']),
            new T(T::TAG, ['else', '']),
            new T(T::TAG_END, 'if'),
        ]);

        $this->assertSame('<?php if ($foo): ?><?php else: ?><?php endif ?>', $p->parse());

        $p = $this->getParser([
            new T(T::TAG_END, 'foreach'),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, ParserException::class, $p);

        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar']),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, ParserException::class, $p);

        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar']),
            new T(T::TAG, ['if', '$foo']),
            new T(T::TAG_END, 'foreach'),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, ParserException::class, $p);

        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar']),
            new T(T::TAG_END, 'if'),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, ParserException::class, $p);
    }

    public function testParsingEcho()
    {
        // TODO Better context testing
        $p = $this->getParser([
            new T(T::OUTPUT, '$foo'),
            new T(T::TEXT, PHP_EOL.'<script src="foo"></script>'.PHP_EOL.'<script>alert('),
            new T(T::OUTPUT, '$foo'),
            new T(T::TEXT, ');</script>'.PHP_EOL.'<div class="foo" onclick="alert('),
            new T(T::OUTPUT, '$foo'),
            new T(T::TEXT, ')" href=\''),
            new T(T::OUTPUT, '$foo'),
            new T(T::TEXT, '\'></div>'),
        ]);

        $this->assertSame(<<<'HTML'
            <?= $_env->escapeHtml($foo) ?>
            <script src="foo"></script>
            <script>alert(<?= $_env->escapeScript($foo) ?>);</script>
            <div class="foo" onclick="alert(<?= $_env->escapeScript($foo) ?>)" href='<?= $_env->escapeAttribute($foo) ?>'></div>
            HTML, $p->parse());
    }

    private function getParser(array $tokens)
    {
        return new Parser($tokens, new OutputCompiler(), new DirectiveCompiler());
    }
}
