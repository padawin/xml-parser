<?php

include_once('XMLParser/File.php');

class XmlParser_FileTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public function setUp()
	{
		$this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
		parent::setUp();
	}

    public function testUnknownFile()
    {
		$name = "/path/to/file";
		$file = XMLParser_File::getFile($name);
		$this->assertTrue($file === null);
	}

    public function testKnownFile()
    {
		$name = $this->bootstrap->getOption('storage_path') . '/data.xml';
		$file = XMLParser_File::getFile($name);
		$this->assertTrue($file instanceof XMLParser_File);
	}

	public function testIncorrectFile()
	{
		$name = $this->bootstrap->getOption('storage_path') . '/data-bad-xml.xml';
		$file = XMLParser_File::getFile($name);
		try {
			$file->parse();
		} catch (Exception $e) {
			$this->assertTrue($e instanceof XMLParser_Exception);
		}

		$this->assertTrue(is_null($file->getResult()));
	}

	public function testCorrectFile()
	{
		$name = $this->bootstrap->getOption('storage_path') . '/data.xml';
		$file = XMLParser_File::getFile($name);
		try {
			$file->parse();
			$this->assertTrue(true);
		} catch (XMLParser_Exception $e) {
			$this->assertTrue(false);
		}
		$this->assertTrue(is_array($file->getResult()));
	}
}
