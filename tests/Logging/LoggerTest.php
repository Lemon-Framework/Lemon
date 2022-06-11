<?php

declare(strict_types=1);

namespace Lemon\Tests\Logging;

use DateTime;
use Lemon\Config\Config;
use Lemon\Kernel\Lifecycle;
use Lemon\Logging\Logger;
use Lemon\Support\Filesystem;
use Lemon\Tests\TestCase;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

class LoggerTest extends TestCase
{
    public function tearDown(): void
    {
        Filesystem::delete(Filesystem::join(__DIR__, 'storage', 'logs'));
    }

    public function testDirectoryCreation()
    {
        $log = new Logger(new Config(new Lifecycle(__DIR__)));
        $this->assertDirectoryExists(Filesystem::join(__DIR__, 'storage', 'logs'));
        $this->assertFileExists(Filesystem::join(__DIR__, 'storage', 'logs', '.gitignore'));
    }

    public function testInterpolation()
    {
        $log = new Logger(new Config(new Lifecycle(__DIR__)));
        $this->assertSame('foo idk ok 10', $log->interpolate('foo {bar} ok {baz}', ['bar' => 'idk', 'baz' => 10]));
    }

    public function testLogging()
    {
        $log = new Logger(new Config(new Lifecycle(__DIR__)));
        $log->log(LogLevel::INFO, '{what} se hrouti', ['what' => 'festival']);
        $log->log(LogLevel::ALERT, '{what} protekaji', ['what' => 'zachody']);
        $log->log(LogLevel::EMERGENCY, 'on proste nema svoje kafe');
        $now = (new DateTime())->format('D M d h:i:s Y');
        $this->assertStringEqualsFile(Filesystem::join(__DIR__, 'storage', 'logs', 'lemon.log'), sprintf(<<<'LOG'
        [%s] INFO: festival se hrouti
        [%s] ALERT: zachody protekaji
        [%s] EMERGENCY: on proste nema svoje kafe

        LOG, $now, $now, $now));
        $this->expectException(InvalidArgumentException::class);
        $log->log('parek', 'hukot');
    }
}
