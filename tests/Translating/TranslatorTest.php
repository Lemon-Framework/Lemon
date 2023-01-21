<?php

declare(strict_types=1);

namespace Lemon\Tests\Translating;

use Lemon\Contracts\Config\Config;
use Lemon\Tests\TestCase;
use Lemon\Translating\Exceptions\TranslatorException;
use Lemon\Translating\Translator;
use Mockery;

/**
 * @internal
 * @coversNothing
 */
class TranslatorTest extends TestCase
{
    public function testText()
    {
        $translator = $this->getTranslator('translations'); 

        $this->assertSame('Welcome to the Lemon Framework', $translator->text('base.title'));
        $translator->locate('cs');
        $this->assertSame('Vitejte v citronove ramopraci', $translator->text('base.title'));
        $translator->locate('sk');
        $this->assertSame('Welcome to the Lemon Framework', $translator->text('base.title'));
        $this->assertSame('Welcome to the Lemon Framework', $translator->text('base.title'));

        $translator->locate('en');
        $this->assertSame('we live i a society', $translator->text('foo.message'));
        $translator->locate('cs');
        $this->assertSame('Pokud tohle ctes tak je mi te za a lito a za b, pokud muzes, hod tam prosimte pavla a ne babise diky', $translator->text('foo.message'));

        $this->assertSame('Pokud tohle ctes tak je mi te za a lito a za b, pokud muzes, hod tam prosimte pavla a ne babise diky', $translator->text('foo.message'));


        $this->assertThrowable(function (Translator $translator) {
            $translator->text('parek');
        }, TranslatorException::class, $translator);
        $this->assertThrowable(function (Translator $translator) {
            $translator->text('base.parek');
        }, TranslatorException::class, $translator);
    }

    public function testDefaultTranslationFile()
    {
        $translator = $this->getTranslator('foo');
        $this->assertSame('Value %field must be numeric.', $translator->text('validation.numeric'));
    }

    public function getTranslator(string $dir): Translator
    {
        $mock = Mockery::mock(Config::class);
        $mock
            ->shouldReceive('file')
            ->andReturn(__DIR__.DIRECTORY_SEPARATOR.$dir)
        ;

        $mock
            ->shouldReceive('get')
            ->andReturn('en')
        ;

        return new Translator($mock);
    }
}
