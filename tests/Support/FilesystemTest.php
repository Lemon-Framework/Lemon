<?php

namespace Lemon\Tests\Support;

use Lemon\Support\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{

    private static $tmp;

    /**
     * @beforeClass
     */
    public static function setUpTempDir()
    {
        self::$tmp = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
        mkdir(self::$tmp);
    }

    public function testWrite()
    {
        $file = self::$tmp . DIRECTORY_SEPARATOR . 'test.txt';
        Filesystem::write($file, 'foo');
        $this->assertFileExists($file); 
    } 
}
