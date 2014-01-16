<?php

include_once('XMLParser/File/Sales.php');

class XMLParser_File_SalesTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	public function setUp()
	{
		$this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
		parent::setUp();
	}

    public function testUnknownFile()
    {
		$name = "/path/to/file";
		$file = XMLParser_File_Sales::getFile($name);
		$this->assertTrue($file === null);
	}

    public function testKnownFile()
    {
		$name = $this->bootstrap->getOption('storage_path') . '/data.xml';
		$file = XMLParser_File_Sales::getFile($name);
		$this->assertTrue($file instanceof XMLParser_File);
	}

	public function testIncorrectFile()
	{
		$name = $this->bootstrap->getOption('storage_path') . '/data-bad-xml.xml';
		$file = XMLParser_File_Sales::getFile($name);
		try {
			$file->parse();
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue($e instanceof XMLParser_Exception);
		}

		$this->assertTrue(is_null($file->getResult()));
	}

	public function testUnreadableFile()
	{
		$name = $this->bootstrap->getOption('storage_path') . '/data-unreadable.xml';
		$file = XMLParser_File_Sales::getFile($name);
		try {
			$file->parse();
			$this->assertTrue(false);
		} catch (XMLParser_Exception $e) {
			$this->assertTrue(true);
		}
	}

	public function testCorrectFile()
	{
		$name = $this->bootstrap->getOption('storage_path') . '/data.xml';
		$file = XMLParser_File_Sales::getFile($name);
		try {
			$file->parse();
			$this->assertTrue(true);
		} catch (XMLParser_Exception $e) {
			$this->assertTrue(false);
		}
		$this->assertTrue(is_array($file->getResult()));
	}

	public function testRenderOnTheFly()
	{
		$expected = '14325|3.37|0.67|1262185020|"LVW6622"
50146|9.18|0.96|1262175540|"HJV7145"
';

		$name = $this->bootstrap->getOption('storage_path') . '/data-micro.xml';
		$file = XMLParser_File_Sales::getFile($name);

		ob_start();
		$file->parse(XMLParser_File::OPTION_EPHEMERAL | XMLParser_File::OPTION_RENDER);
		$out = ob_get_contents();
		ob_end_clean();
		$this->assertTrue($out == $expected);
	}
}
