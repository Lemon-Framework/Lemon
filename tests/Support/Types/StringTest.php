<?php

namespace Lemon\Tests\Types;

use Lemon\Support\Types\String_;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class StringTest extends TestCase
{
    public function testSize()
    {
        $string = String_::from('klobna');
        $this->assertSame(6, $string->size());
        $this->assertSame(6, $string->len());
    }

    public function testToString()
    {
        $string = String_::from('FidoKul');
        $this->assertSame('FidoKul is podvodnik', $string.' is podvodnik');
    }

    public function testSplit()
    {
        $string = String_::from('foo|bar|baz');
        $this->assertSame(['foo', 'bar', 'baz'], $string->split('|')->content);
    }

    public function testJoin()
    {
        $string = String_::from('|');
        $this->assertSame('foo|bar|baz', $string->join(['foo', 'bar', 'baz'])->content);
    }

    public function testCapitalize()
    {
        $string = String_::from('vrtacka');
        $string->capitalize();
        $this->assertSame('Vrtacka', $string->content);
    }

    public function testDecapitalize()
    {
        $string = String_::from('Vrtacka');
        $string->decapitalize();
        $this->assertSame('vrtacka', $string->content);
    }

    public function testToLower()
    {
        $string = String_::from('KlObAsA vE sLEvE');
        $this->assertSame('klobasa ve sleve', $string->toLower()->content);
    }

    public function testToUpper()
    {
        $string = String_::from('KlObAsA vE sLEvE');
        $this->assertSame('KLOBASA VE SLEVE', $string->toUpper()->content);
    }

    public function testContains()
    {
        $string = String_::from('lemon');
        $this->assertTrue($string->contains('emo'));
        $string = String_::from('JESTLI TOHLE NEKDO CTE TAK JE MI HO OPRAVDU LITO');
        $this->assertFalse($string->has('lemon'));
    }

    public function testStartsWith()
    {
        $string = String_::from('vrtacka');
        $this->assertTrue($string->startsWith('vrt'));
        $string = String_::from('laravel');
        $this->assertFalse($string->startsWith('ok'));
    }

    public function testEndsWith()
    {
        $string = String_::from('klobasa');
        $this->assertTrue($string->endsWith('basa'));
        $string = String_::from('test.wal');
        $this->assertFalse($string->endsWith('.php'));
    }

    public function testReplace()
    {
        $string = String_::from('Vim is awesome');
        $string->replace('Vim', 'Neovim');
        $this->assertSame('Neovim is awesome', $string->content);
    }

    public function testReverse()
    {
        $string = String_::from('mixer');
        $string->reverse();
        $this->assertSame('rexim', $string->content);
    }
}
