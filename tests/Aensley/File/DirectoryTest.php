<?php

namespace Aensley\File;

use Aensley\File\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    private $directory;
    private $directoryName;
    private $directoryBaseName;
    private $newDirectory;
    private $nonExistentDirectory;


    protected function setUp(): void
    {
        $this->directory = new Directory();
        $this->directoryName = dirname(realpath(__FILE__));
        $this->directoryBaseName = 'new_directory';
        $this->newDirectory = $this->directoryName . DIRECTORY_SEPARATOR
            . $this->directoryBaseName . DIRECTORY_SEPARATOR;
        $this->nonExistentDirectory = $this->directoryName . DIRECTORY_SEPARATOR . 'idontexist' . DIRECTORY_SEPARATOR;
    }


    public function tearDown(): void
    {
        Directory::delete($this->newDirectory);
    }


    public function testInstantiation()
    {
        $this->assertInstanceOf('\Aensley\File\Directory', $this->directory);
    }


    public function testCreateAndExists()
    {
        $this->assertTrue(Directory::create($this->newDirectory));
        $this->assertTrue(Directory::exists($this->newDirectory));
    }


    public function testDelete()
    {
        @Directory::create($this->newDirectory);
        // Create sub-file to test recursive delete.
        touch($this->newDirectory . 'somefile');
        $this->assertTrue(Directory::delete($this->newDirectory));
        $this->assertTrue(Directory::delete($this->nonExistentDirectory));
    }


    public function testIsWritable()
    {
        @Directory::create($this->newDirectory);
        $this->assertTrue(Directory::isWritable($this->newDirectory));
        $this->assertFalse(Directory::isWritable($this->nonExistentDirectory));
    }


    public function testIsReadable()
    {
        @Directory::create($this->newDirectory);
        $this->assertTrue(Directory::isReadable($this->newDirectory));
        $this->assertFalse(Directory::isReadable($this->nonExistentDirectory));
    }


    public function testDeleteWithNestedSubdirectory()
    {
        mkdir($this->newDirectory . 'subdir', 0755, true);
        touch($this->newDirectory . 'subdir' . DIRECTORY_SEPARATOR . 'file.txt');
        $this->assertTrue(Directory::delete($this->newDirectory));
        $this->assertFalse(Directory::exists($this->newDirectory));
    }


    public function testListFiles()
    {
        @Directory::create($this->newDirectory);
        touch($this->newDirectory . 'somefile.csv');
        touch($this->newDirectory . 'somefile.txt');
        touch($this->newDirectory . 'somefile.xml');
        $files = Directory::listFiles($this->newDirectory);
        $this->assertEquals($files, [
            $this->newDirectory . 'somefile.csv',
            $this->newDirectory . 'somefile.txt',
            $this->newDirectory . 'somefile.xml',
        ]);
        $files = Directory::listFiles($this->newDirectory, true, ['txt', 'xml']);
        $this->assertEquals($files, [
            $this->newDirectory . 'somefile.txt',
            $this->newDirectory . 'somefile.xml',
        ]);
        $files = Directory::listFiles($this->newDirectory, true, ['txt']);
        $this->assertEquals($files, [
            $this->newDirectory . 'somefile.txt',
        ]);
    }


    public function testListFilesWithSymlinkAndRecursion()
    {
        Directory::create($this->newDirectory);
        touch($this->newDirectory . 'somefile.txt');

        // Symlink — listFiles should skip it (covers Directory.php line 130)
        $symlinkTarget = $this->directoryName . DIRECTORY_SEPARATOR . 'symlink_target.txt';
        touch($symlinkTarget);
        symlink($symlinkTarget, $this->newDirectory . 'alink');

        // Subdirectory with a file — recursive listing covers Directory.php lines 134, 136, 140
        mkdir($this->newDirectory . 'subdir', 0755, true);
        touch($this->newDirectory . 'subdir' . DIRECTORY_SEPARATOR . 'nested.txt');

        $files = Directory::listFiles($this->newDirectory, true);
        $this->assertContains($this->newDirectory . 'somefile.txt', $files);
        $this->assertContains($this->newDirectory . 'subdir' . DIRECTORY_SEPARATOR . 'nested.txt', $files);
        $this->assertNotContains($this->newDirectory . 'alink', $files);

        // Non-recursive — subdir files should not appear
        $files = Directory::listFiles($this->newDirectory);
        $this->assertContains($this->newDirectory . 'somefile.txt', $files);
        $this->assertNotContains($this->newDirectory . 'subdir' . DIRECTORY_SEPARATOR . 'nested.txt', $files);

        @unlink($symlinkTarget);
    }
}
