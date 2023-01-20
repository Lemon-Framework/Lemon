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
        $mock = Mockery::mock(Config::class);
        $mock
            ->shouldReceive('file')
            ->andReturn(__DIR__.DIRECTORY_SEPARATOR.'translations')
        ;

        $mock
            ->shouldReceive('get')
            ->andReturn('en')
        ;

        $translator = new Translator($mock);
        $this->assertSame('Welcome to the Lemon Framework', $translator->text('base.title'));
        $translator->locate('cs');
        $this->assertSame('Vitejte v citronove ramopraci', $translator->text('base.title'));
        $translator->locate('sk');
        $this->assertSame('Welcome to the Lemon Framework', $translator->text('base.title'));

        $translator->locate('en');
        $this->assertSame('we live i a society', $translator->text('foo.message'));
        $translator->locate('cs');
        $this->assertSame('Pokud tohle ctes tak je mi te za a lito a za b, pokud muzes, hod tam prosimte pavla a ne babise diky', $translator->text('foo.message'));
        $this->assertThrowable(function (Translator $translator) {
            $translator->text('parek');
        }, TranslatorException::class, $translator);
        $this->assertThrowable(function (Translator $translator) {
            $translator->text('base.parek');
        }, TranslatorException::class, $translator);
    }
}
