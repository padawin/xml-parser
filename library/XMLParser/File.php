<?php

include_once('XMLParser/Exception.php');

abstract class XMLParser_File
{
	/**
	 * XML file
	 */
	protected $_file;

	/**
	 * Content of the XML file
	 */
	protected $_result;


	protected $_parser;

	/**
	 * Method to create a object representing a xml file from the file name.
	 * If no file is found, null is returned, else a XMLParser_File instance
	 * is created
	 *
	 * @param $fileName string path to the file
	 *
	 * @return XMLParser_File|null
	 */
	public static function getFile($fileName)
	{
		if (!file_exists($fileName)) {
			return null;
		}

		return new static($fileName);
	}

	/**
	 * Class's construct. Set protected to use the getFile method.
	 */
	protected function __construct($fileName)
	{
		$this->_file = $fileName;
		$this->_parser = xml_parser_create("UTF-8");
		xml_set_object($this->_parser, $this);
		xml_set_element_handler($this->_parser, "startTag", "endTag");
		xml_set_character_data_handler($this->_parser, "tagContent");
	}

	/**
	 * Method called at the begining of the tag parsing
	 */
	public abstract function startTag($parser, $name, $attribs = array());

	/**
	 * Method called to process the tag content
	 */
	public abstract function tagContent($parser, $content);

	/**
	 * Method called at the end of the tag parsing
	 */
	public abstract function endTag($parser, $name, $attribs = array());

	/**
	 * Method which will parse the xml file and set the result in a class
	 * attribute.
	 */
	public function parse()
	{
		$fh = fopen($this->_file, "r");
		if (!$fh) {
			throw new XMLParser_Exception("Cannot open file {$this->_file}");
		}

		while (!feof($fh)) {
			$data = fread($fh, 4096);
			xml_parse($this->_parser, $data, feof($fh));
		}

		fclose($fh);
		xml_parser_free($this->_parser);
	}

	/**
	 * Returns the content of the XML file.
	 *
	 * @return array
	 */
	public function getResult()
	{
		return $this->_result;
	}
}
