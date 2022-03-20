<?php

namespace Lemon\Tests\Support;

use Lemon\Exceptions\FilesystemException;
use Lemon\Support\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class FilesystemTest extends TestCase
{
    private static $tmp;

    public static function setUpBeforeClass(): void
    {
        self::$tmp = __DIR__.DIRECTORY_SEPARATOR.'tmp';
        mkdir(self::$tmp);
    }

    public static function tearDownAfterClass(): void
    {
        rmdir(self::$tmp);
    }

    public function testRead()
    {
        $file = self::$tmp.DIRECTORY_SEPARATOR.'test.txt';
        file_put_contents($file, 'foo');
        $this->assertSame('foo', Filesystem::read($file));
        unlink($file);
    }

    public function testWrite()
    {
        $file = self::$tmp.DIRECTORY_SEPARATOR.'test.txt';
        fclose(fopen($file, "w"));
        
        Filesystem::write($file, 'bar');
        $this->assertSame('bar', file_get_contents($file));
        
        unlink($file);

        $this->expectException(FilesystemException::class);
        Filesystem::write($file, 'foo');
    }
    
    public function testMakeDir()
    {
        $dir = self::$tmp.DIRECTORY_SEPARATOR.'test';
        Filesystem::makeDir($dir);
        $this->assertDirectoryExists($dir, $dir);
        rmdir($dir);
        Filesystem::makeDir($dir.DIRECTORY_SEPARATOR);
        $this->assertDirectoryExists($dir);
        rmdir($dir);
        $this->expectException(FilesystemException::class);
        Filesystem::makeDir(self::$tmp);
    }

    public function testTouch()
    {
        $file = self::$tmp.DIRECTORY_SEPARATOR.'code_20320';
        Filesystem::touch($file);
        $this->assertFileExists($file);
        unlink($file);
        $this->expectException(FilesystemException::class);
        Filesystem::touch(__FILE__);  // ?     
    }

}
