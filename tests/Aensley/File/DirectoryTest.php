<?php

use \Aensley\File\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{

	private $directory;
	private $directoryName;
	private $directoryBaseName;
	private $newDirectory;
	private $nonExistentDirectory;


	protected function setUp()
	{
		$this->directory = new Directory();
		$this->directoryName = dirname(realpath(__FILE__));
		$this->directoryBaseName = 'new_directory';
		$this->newDirectory = $this->directoryName . DIRECTORY_SEPARATOR . $this->directoryBaseName . DIRECTORY_SEPARATOR;
		$this->nonExistentDirectory = $this->directoryName . DIRECTORY_SEPARATOR . 'idontexist' . DIRECTORY_SEPARATOR;
	}


	public function tearDown()
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


	public function testListFiles()
	{
		@Directory::create($this->newDirectory);
		touch($this->newDirectory . 'somefile.csv');
		touch($this->newDirectory . 'somefile.txt');
		touch($this->newDirectory . 'somefile.xml');
		$files = Directory::listFiles($this->newDirectory);
		$this->assertEquals($files, array(
			$this->newDirectory . 'somefile.csv',
			$this->newDirectory . 'somefile.txt',
			$this->newDirectory . 'somefile.xml',
		));
		$files = Directory::listFiles($this->newDirectory, true, array('txt', 'xml'));
		$this->assertEquals($files, array(
			$this->newDirectory . 'somefile.txt',
			$this->newDirectory . 'somefile.xml',
		));
		$files = Directory::listFiles($this->newDirectory, true, array('txt'));
		$this->assertEquals($files, array(
			$this->newDirectory . 'somefile.txt',
		));
	}
}
