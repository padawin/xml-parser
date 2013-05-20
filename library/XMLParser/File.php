<?php

include_once('XMLParser/Exception.php');

class XMLParser_File
{
	/**
	 * XML file
	 */
	protected $_file;

	/**
	 * Content of the XML file
	 */
	protected $_result;

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
	}

	/**
	 * Method which will parse the xml file and set the result in a class
	 * attribute.
	 */
	public function parse()
	{
		libxml_use_internal_errors(true);
		$XMLFile = (array) simplexml_load_file($this->_file);
		$errors = libxml_get_errors();
		libxml_clear_errors();
		if (!empty($errors)) {
			throw new XMLParser_Exception(
				"Invalid XML file provided ({$this->_file})"
			);
		}

		foreach ($XMLFile['sale'] as $key => $row) {
			$XMLFile['sale'][$key] = (array) $row;
		}
		$this->_result = $XMLFile;
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
