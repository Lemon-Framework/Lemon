<?php

declare(strict_types=1);

namespace Lemon\Tests\Validation;

use Lemon\Tests\TestCase;
use Lemon\Validation\Rules;

/**
 * @internal
 *
 * @coversNothing
 */
class RulesTest extends TestCase
{
    public function testNumeric()
    {
        $r = new Rules();
        $this->assertTrue($r->numeric('37'));
        $this->assertFalse($r->numeric('parek'));

        $this->assertFalse($r->notNumeric('37'));
        $this->assertTrue($r->notNumeric('parek'));
    }

    public function testEmail()
    {
        $r = new Rules();
        $this->assertTrue($r->email('x@y.cz'));
        $this->assertFalse($r->email('x.cz'));
    }

    public function testUrl()
    {
        $r = new Rules();
        $this->assertTrue($r->url('https://foo.bar/parek'));
        $this->assertFalse($r->url('foo.bar/parek'));
    }

    public function testColor()
    {
        $r = new Rules();
        $this->assertTrue($r->color('#FfbB00'));
        $this->assertTrue($r->color('#ffb'));
        $this->assertFalse($r->color('ffbb00'));
        $this->assertFalse($r->color('#fbb00'));
        $this->assertFalse($r->color('#fbbG00'));
    }

    public function testMin()
    {
        $r = new Rules();
        $this->assertTrue($r->min('foo', 2));
        $this->assertTrue($r->min('foo', 3));
        $this->assertFalse($r->min('f', 2));
    }

    public function testMax()
    {
        $r = new Rules();
        $this->assertTrue($r->max('foo', 4));
        $this->assertTrue($r->max('foo', 3));
        $this->assertFalse($r->max('fooo', 2));
    }

    public function testRe()
    {
        $r = new Rules();
        $this->assertTrue($r->regex('foo', 'f(oo)?'));
        $this->assertFalse($r->regex('fo', 'f(oo)?'));

        $this->assertFalse($r->notRegex('foo', 'f(oo)?'));
        $this->assertTrue($r->notRegex('fo', 'f(oo)?'));
    }

    public function testContains()
    {
        $r = new Rules();
        $this->assertTrue($r->contains('ofooooo', 'f(oo)?'));
        $this->assertFalse($r->contains('ppprr', 'f(oo)?'));

        $this->assertFalse($r->doesntContain('pasdfooooooo', 'f(oo)?'));
        $this->assertTrue($r->doesntContain('asjpkhjasiop', 'f(oo)?'));
    }

    public function testCalling()
    {
        $r = new Rules();
        $this->assertTrue($r->call('parek', ['min', '1']));
        $this->assertTrue($r->call('p@a.rek', ['email']));
        $r->rule('name', fn ($name) => ucfirst($name) === $name);
        $this->assertTrue($r->call('Parek', ['name']));
    }

    public function testDate()
    {
        $r = new Rules();
        $this->assertTrue($r->date('2020-01-01'));
        $this->assertFalse($r->date('2020-01-32'));
        $this->assertFalse($r->date('202-1-2'));
    }

    public function testDateTime()
    {
        $r = new Rules();
        $this->assertTrue($r->datetime('2020-01-01T12:00'));
        $this->assertFalse($r->datetime('2020-01-32T12:00'));
        $this->assertFalse($r->datetime('202-1-2T12:00'));
        $this->assertFalse($r->datetime('2023-10-02T1:00'));
        $this->assertFalse($r->datetime('2023-10-02T1:000'));
        $this->assertFalse($r->datetime('2023-10-02 1:000'));
    }

    public function testInteger()
    {
        $r = new Rules();
        $this->assertTrue($r->integer('1'));
        $this->assertTrue($r->integer('0'));
        $this->assertTrue($r->integer('-1'));
        $this->assertFalse($r->integer('1.5'));
        $this->assertFalse($r->integer('0.5'));
        $this->assertFalse($r->integer('-1.5'));
    }

    public function testLt()
    {
        $r = new Rules();
        $this->assertFalse($r->lt('0', '0'));
        $this->assertFalse($r->lt('10', '10'));
        $this->assertFalse($r->lt('-5', '-5'));
        $this->assertFalse($r->lt('2.3', '2.3'));
        $this->assertFalse($r->lt('-2.3', '-2.3'));
        $this->assertTrue($r->lt('0', '1'));
        $this->assertFalse($r->lt('1', '0'));
        $this->assertTrue($r->lt('-1', '0'));
        $this->assertFalse($r->lt('0', '-1'));
        $this->assertTrue($r->lt('-0.5', '0'));
        $this->assertFalse($r->lt('0', '-0.5'));
        $this->assertFalse($r->lt('0.5', '0'));
        $this->assertTrue($r->lt('0', '0.5'));
        $this->assertTrue($r->lt('24', '42'));
        $this->assertFalse($r->lt('42', '24'));
        $this->assertTrue($r->lt('-10', '50'));
        $this->assertFalse($r->lt('50', '-10'));
        $this->assertTrue($r->lt('2.5', '3.2'));
        $this->assertFalse($r->lt('3.2', '2.5'));
        $this->assertTrue($r->lt('-1.2', '3.1'));
        $this->assertFalse($r->lt('3.1', '-1.2'));
    }

    public function testLte()
    {
        $r = new Rules();
        $this->assertTrue($r->lte('0', '0'));
        $this->assertTrue($r->lte('10', '10'));
        $this->assertTrue($r->lte('-5', '-5'));
        $this->assertTrue($r->lte('2.3', '2.3'));
        $this->assertTrue($r->lte('-2.3', '-2.3'));
        $this->assertTrue($r->lte('0', '1'));
        $this->assertFalse($r->lte('1', '0'));
        $this->assertTrue($r->lte('-1', '0'));
        $this->assertFalse($r->lte('0', '-1'));
        $this->assertTrue($r->lte('-0.5', '0'));
        $this->assertFalse($r->lte('0', '-0.5'));
        $this->assertFalse($r->lte('0.5', '0'));
        $this->assertTrue($r->lte('0', '0.5'));
        $this->assertTrue($r->lte('24', '42'));
        $this->assertFalse($r->lte('42', '24'));
        $this->assertTrue($r->lte('-10', '50'));
        $this->assertFalse($r->lte('50', '-10'));
        $this->assertTrue($r->lte('2.5', '3.2'));
        $this->assertFalse($r->lte('3.2', '2.5'));
        $this->assertTrue($r->lte('-1.2', '3.1'));
        $this->assertFalse($r->lte('3.1', '-1.2'));
    }

    public function testGt()
    {
        $r = new Rules();
        $this->assertFalse($r->gt('0', '0'));
        $this->assertFalse($r->gt('10', '10'));
        $this->assertFalse($r->gt('-5', '-5'));
        $this->assertFalse($r->gt('2.3', '2.3'));
        $this->assertFalse($r->gt('-2.3', '-2.3'));
        $this->assertFalse($r->gt('0', '1'));
        $this->assertTrue($r->gt('1', '0'));
        $this->assertFalse($r->gt('-1', '0'));
        $this->assertTrue($r->gt('0', '-1'));
        $this->assertFalse($r->gt('-0.5', '0'));
        $this->assertTrue($r->gt('0', '-0.5'));
        $this->assertTrue($r->gt('0.5', '0'));
        $this->assertFalse($r->gt('0', '0.5'));
        $this->assertFalse($r->gt('24', '42'));
        $this->assertTrue($r->gt('42', '24'));
        $this->assertFalse($r->gt('-10', '50'));
        $this->assertTrue($r->gt('50', '-10'));
        $this->assertFalse($r->gt('2.5', '3.2'));
        $this->assertTrue($r->gt('3.2', '2.5'));
        $this->assertFalse($r->gt('-1.2', '3.1'));
        $this->assertTrue($r->gt('3.1', '-1.2'));
    }

    public function testGte()
    {
        $r = new Rules();
        $this->assertTrue($r->gte('0', '0'));
        $this->assertTrue($r->gte('10', '10'));
        $this->assertTrue($r->gte('-5', '-5'));
        $this->assertTrue($r->gte('2.3', '2.3'));
        $this->assertTrue($r->gte('-2.3', '-2.3'));
        $this->assertFalse($r->gte('0', '1'));
        $this->assertTrue($r->gte('1', '0'));
        $this->assertFalse($r->gte('-1', '0'));
        $this->assertTrue($r->gte('0', '-1'));
        $this->assertFalse($r->gte('-0.5', '0'));
        $this->assertTrue($r->gte('0', '-0.5'));
        $this->assertTrue($r->gte('0.5', '0'));
        $this->assertFalse($r->gte('0', '0.5'));
        $this->assertFalse($r->gte('24', '42'));
        $this->assertTrue($r->gte('42', '24'));
        $this->assertFalse($r->gte('-10', '50'));
        $this->assertTrue($r->gte('50', '-10'));
        $this->assertFalse($r->gte('2.5', '3.2'));
        $this->assertTrue($r->gte('3.2', '2.5'));
        $this->assertFalse($r->gte('-1.2', '3.1'));
        $this->assertTrue($r->gte('3.1', '-1.2'));
    }

    public function testYear()
    {
        $r = new Rules();
        $this->assertTrue($r->year('0'));
        $this->assertTrue($r->year('1984'));
        $this->assertTrue($r->year('2023'));
        $this->assertFalse($r->year('-5'));
        $this->assertFalse($r->year('3.141'));
        $this->assertTrue($r->year((string) (intval(date('Y')) + -1))); // Previous year
        $this->assertTrue($r->year(date('Y')));
        $this->assertTrue($r->year((string) (intval(date('Y')) + 1))); // Next year
    }

    public function testPassedYear()
    {
        $r = new Rules();
        $this->assertTrue($r->passedYear('0'));
        $this->assertTrue($r->passedYear('1984'));
        $this->assertFalse($r->passedYear('-5'));
        $this->assertFalse($r->passedYear('3.141'));
        $this->assertTrue($r->passedYear((string) (intval(date('Y')) + -1))); // Previous year
        $this->assertFalse($r->passedYear(date('Y')));
        $this->assertFalse($r->passedYear((string) (intval(date('Y')) + 1))); // Next year

    public function testBoolean()
    {
        $r = new Rules();
        $this->assertTrue($r->boolean('true'));
        $this->assertTrue($r->boolean('false'));
        $this->assertTrue($r->boolean('1'));
        $this->assertTrue($r->boolean('0'));
        $this->assertTrue($r->boolean('on'));
        $this->assertTrue($r->boolean('off'));
        $this->assertTrue($r->boolean('yes'));
        $this->assertTrue($r->boolean('no'));
        $this->assertFalse($r->boolean(''));
        $this->assertFalse($r->boolean('asdf'));
        $this->assertFalse($r->boolean('42'));
    }
}
