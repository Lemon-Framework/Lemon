<?php

declare(strict_types=1);

namespace Lemon\Tests\Templating\Juice;

use Lemon\Templating\Juice\Compilers\DirectiveCompiler;
use Lemon\Templating\Juice\Compilers\OutputCompiler;
use Lemon\Templating\Juice\Exceptions\CompilerException;
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
            new T(T::TAG, ['foreach', '$foo as $bar'], 1),
            new T(T::TAG_END, 'foreach', 1),
        ]);

        $this->assertSame('<?php foreach ($foo as $bar): ?><?php endforeach ?>', $p->parse());

        $p = $this->getParser([
            new T(T::TAG, ['if', '$foo'], 1),
            new T(T::TAG, ['else', ''], 1),
            new T(T::TAG_END, 'if', 1),
        ]);

        $this->assertSame('<?php if ($foo): ?><?php else: ?><?php endif ?>', $p->parse());

        $p = $this->getParser([
            new T(T::TAG_END, 'foreach', 1),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, CompilerException::class, $p);

        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar'], 1),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, CompilerException::class, $p);

        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar'], 1),
            new T(T::TAG, ['if', '$foo'], 1),
            new T(T::TAG_END, 'foreach', 1),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, CompilerException::class, $p);

        $p = $this->getParser([
            new T(T::TAG, ['foreach', '$foo as $bar'], 1),
            new T(T::TAG_END, 'if', 1),
        ]);

        $this->assertThrowable(function (Parser $p) {
            $p->parse();
        }, CompilerException::class, $p);
    }

    public function testParsingEcho()
    {
        // TODO Better context testing
        $p = $this->getParser([
            new T(T::OUTPUT, '$foo', 1),
            new T(T::TEXT, PHP_EOL.'<script src="foo"></script>'.PHP_EOL.'<script>alert(', 1),
            new T(T::OUTPUT, '$foo', 3),
            new T(T::TEXT, ');</script>'.PHP_EOL.'<div class="foo" onclick="alert(', 3),
            new T(T::OUTPUT, '$foo', 4),
            new T(T::TEXT, ')" href=\'', 4),
            new T(T::OUTPUT, '$foo', 4),
            new T(T::TEXT, '\'></div>', 4),
        ]);

        $this->assertSame(<<<'HTML'
            <?php echo $_env->escapeHtml($foo) ?>
            <script src="foo"></script>
            <script>alert(<?php echo $_env->escapeScript($foo) ?>);</script>
            <div class="foo" onclick="alert(<?php echo $_env->escapeScript($foo) ?>)" href='<?php echo $_env->escapeAttribute($foo) ?>'></div>
            HTML, $p->parse());
    }

    private function getParser(array $tokens)
    {
        return new Parser($tokens, new OutputCompiler(), new DirectiveCompiler());
    }
}
