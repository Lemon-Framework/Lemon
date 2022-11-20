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
        $this->assertSame('Welcome to the Lemon Framework', $translator->text('title'));
        $translator->locate('cs');
        $this->assertSame('Vitejte v citronove ramopraci', $translator->text('title'));
        $translator->locate('sk');
        $this->assertSame('Welcome to the Lemon Framework', $translator->text('title'));
        $this->assertThrowable(function (Translator $translator) {
            $translator->text('parek');
        }, TranslatorException::class, $translator);
    }
}
