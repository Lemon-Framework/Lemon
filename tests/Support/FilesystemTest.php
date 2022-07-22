<?php

declare(strict_types=1);

namespace Lemon\Tests\Support;

use Exception;
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

    public function createRecursiveDir($where)
    {
        $s = DIRECTORY_SEPARATOR;
        mkdir($where);
        touch($where.$s.'foo.php');
        touch($where.$s.'bar.php');
        mkdir($where.$s.'kombajn'.$s.'traktor', recursive: true);
        touch($where.$s.'kombajn'.$s.'parek.php');
        touch($where.$s.'kombajn'.$s.'traktor'.$s.'rizek.php');
        mkdir($where.$s.'lisky');
    }

    public function removeRecursiveDir($where)
    {
        $s = DIRECTORY_SEPARATOR;
        unlink($where.$s.'foo.php');
        unlink($where.$s.'bar.php');
        unlink($where.$s.'kombajn'.$s.'parek.php');
        unlink($where.$s.'kombajn'.$s.'traktor'.$s.'rizek.php');
        rmdir($where.$s.'kombajn'.$s.'traktor');
        rmdir($where.$s.'kombajn'.$s);
        rmdir($where.$s.'lisky');
        rmdir($where);
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
        touch($file);

        Filesystem::write($file, 'bar');
        $this->assertSame('bar', file_get_contents($file));

        unlink($file);

        Filesystem::write($file, 'foo');
        $this->assertFileExists($file);
        unlink($file);
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

    public function testIsFile()
    {
        $file = self::$tmp.DIRECTORY_SEPARATOR.'traktor';
        touch($file);
        $this->assertTrue(Filesystem::isFile($file));
        unlink($file);
        $this->assertFalse(Filesystem::isFile($file));
        $this->assertFalse(Filesystem::isFile(self::$tmp));
    }

    public function testIsDir()
    {
        $this->assertTrue(Filesystem::isDir(self::$tmp));
        $file = self::$tmp.DIRECTORY_SEPARATOR.'traktor';
        touch($file);
        $this->assertFalse(Filesystem::isDir($file));
        unlink($file);
        $this->assertFalse(Filesystem::isDir('kombajn'));
    }

    public function testJoin()
    {
        $s = DIRECTORY_SEPARATOR;
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::join('foo', 'klobna.php'));
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::join('foo'.$s.$s.$s, 'klobna.php'));
        $this->assertSame($s.'foo'.$s.'klobna.php', Filesystem::join($s.'foo', 'klobna.php'));
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::join('foo', 'klobna.php'.$s.$s.$s));
        $this->expectException(Exception::class);
        Filesystem::join('parek');
        Filesystem::join();
    }

    public function testDelete()
    {
        $dir = self::$tmp.DIRECTORY_SEPARATOR.'traktor';
        touch($dir);
        Filesystem::delete($dir);
        $this->assertFileDoesNotExist($dir);

        mkdir($dir);
        Filesystem::delete($dir);
        $this->assertDirectoryDoesNotExist($dir);

        $this->createRecursiveDir($dir);
        Filesystem::delete($dir);
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function testNormalize()
    {
        $s = DIRECTORY_SEPARATOR;
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::normalize('foo/klobna.php'));
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::normalize('foo\klobna.php'));
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::normalize('foo\\klobna.php'));
        $this->assertSame('foo'.$s.'klobna', Filesystem::normalize('foo/klobna/'));
        $this->assertSame('foo'.$s.'klobna', Filesystem::normalize('foo\klobna\\'));
        $this->assertSame('foo'.$s.'klobna.php', Filesystem::normalize('foo/////klobna.php'));
    }

    public function testParent()
    {
        $s = DIRECTORY_SEPARATOR;
        $this->assertSame('foo', Filesystem::parent('foo'.$s.'bar.php'));
        $this->assertSame('foo'.$s.'klobna', Filesystem::parent('foo'.$s.'klobna'.$s.'bar.php'));
        $this->assertSame('', Filesystem::parent('foo'));
    }

    public function testListDir()
    {
        $s = DIRECTORY_SEPARATOR;
        $base = self::$tmp.$s.'klobna';
        $this->createRecursiveDir($base);
        $list = Filesystem::listDir($base);
        $this->assertSame([
            $base.$s.'bar.php',
            $base.$s.'foo.php',
            $base.$s.'kombajn'.$s.'parek.php',
            $base.$s.'kombajn'.$s.'traktor'.$s.'rizek.php',
            $base.$s.'lisky',
        ], $list);
        $this->removeRecursiveDir($base);
    }
}
