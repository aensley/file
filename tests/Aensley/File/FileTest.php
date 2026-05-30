<?php

namespace Aensley\File;

use Aensley\File\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $file;
    private $fileName;
    private $directoryName;
    private $fileBaseName;
    private $nonExistentFile;
    private $exifFile;
    private $newFile;


    protected function setUp(): void
    {
        $this->file = new File();
        $this->directoryName = dirname(realpath(__FILE__));
        $this->fileBaseName = 'test.txt';
        $this->fileName = $this->directoryName . DIRECTORY_SEPARATOR . $this->fileBaseName;
        $this->newFile = $this->directoryName . DIRECTORY_SEPARATOR . 'newtest.txt';
        $this->exifFile = realpath(dirname(__FILE__) . '/../../../assets/') . '/test_exif_july_5_2016.jpg';
        $this->nonExistentFile = $this->directoryName . DIRECTORY_SEPARATOR . 'idontexist.txt';
        touch($this->fileName);
    }


    public function testInstantiation()
    {
        $this->assertInstanceOf('\Aensley\File\File', $this->file);
    }


    public function testExtension()
    {
        $this->assertEquals('txt', File::extension($this->fileName));
    }


    public function testName()
    {
        $this->assertEquals('test', File::name($this->fileName));
    }


    public function testExifDateTime()
    {
        $this->assertEquals('2016-07-05 09:54:55', File::exifDateTime($this->exifFile, self::DATE_FORMAT));
        // Plain text file has no EXIF data — covers File.php line 67
        $this->assertEquals('', File::exifDateTime($this->fileName));
    }


    public function testModifiedDateTime()
    {
        $now = time();
        touch($this->fileName, $now);
        $this->assertEquals(date(self::DATE_FORMAT, $now), File::modifiedDateTime($this->fileName, self::DATE_FORMAT));
    }


    public function testDelete()
    {
        touch($this->fileName);
        $this->assertTrue(File::delete($this->fileName));
        $this->assertFileDoesNotExist($this->fileName);
        $this->assertFalse(File::delete($this->nonExistentFile));
        touch($this->fileName);
    }


    public function testExists()
    {
        touch($this->fileName);
        $this->assertTrue(File::exists($this->fileName));
        $this->assertFalse(File::exists($this->nonExistentFile));
    }


    public function testIsReadable()
    {
        touch($this->fileName);
        $this->assertTrue(File::isReadable($this->fileName));
        $this->assertFalse(File::isReadable($this->nonExistentFile));
    }


    public function testIsWritable()
    {
        touch($this->fileName);
        $this->assertTrue(File::isWritable($this->fileName));
        $this->assertFalse(File::isWritable($this->nonExistentFile));
    }
}
