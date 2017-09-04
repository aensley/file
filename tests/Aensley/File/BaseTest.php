<?php

use \Aensley\File\Base;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{

	private $base;
	private $fileName;
	private $directoryName;
	private $fileBaseName;
	private $nonExistentFile;
	private $newFile;


	protected function setUp()
	{
		$this->base = new Base();
		$this->directoryName = dirname(realpath(__FILE__));
		$this->fileBaseName = 'test.txt';
		$this->fileName = $this->directoryName . DIRECTORY_SEPARATOR . $this->fileBaseName;
		$this->newFile = $this->directoryName . DIRECTORY_SEPARATOR . 'newtest.txt';
		$this->nonExistentFile = $this->directoryName . DIRECTORY_SEPARATOR . 'idontexist.txt';
		touch($this->fileName);
	}


	public function tearDown()
	{
		@unlink($this->newFile);
		for ($i = 0; $i < 20; $i++) {
			@unlink($this->directoryName . DIRECTORY_SEPARATOR . 'newtest_' . $i . '.txt');
		}
	}


	public function testInstantiation()
	{
		$this->assertInstanceOf('\Aensley\File\Base', $this->base);
	}


	public function testDirectoryName()
	{
		$this->assertEquals($this->directoryName, Base::directoryName($this->fileName));
	}


	public function testBaseName()
	{
		$this->assertEquals($this->fileBaseName, Base::baseName($this->fileName));
	}


	public function testExists()
	{
		$this->assertTrue(Base::exists($this->fileName));
		$this->assertFalse(Base::exists($this->nonExistentFile));
	}


	public function testIsReadable()
	{
		$this->assertTrue(Base::isReadable($this->fileName));
		$this->assertFalse(Base::isReadable($this->nonExistentFile));
	}


	public function testIsWritable()
	{
		$this->assertTrue(Base::isWritable($this->fileName));
		$this->assertFalse(Base::isWritable($this->nonExistentFile));
	}


	public function testMove()
	{
		$file = Base::move($this->fileName, $this->newFile);
		$this->assertEquals($this->newFile, $file);
		$this->assertFileExists($file);
		for ($i = 0; $i < 20; $i++) {
			touch($this->fileName);
			$file = Base::move($this->fileName, $this->newFile, true);
			$this->assertEquals($this->directoryName . DIRECTORY_SEPARATOR . 'newtest_' . $i . '.txt', $file);
			$this->assertFileExists($file);
		}
	}
}
