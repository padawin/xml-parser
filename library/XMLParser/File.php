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
	 * Boolean If true, the rows will be rendered when parsed, in the endTag call.
	 */
	protected $_doRender = false;

	/**
	 * Boolean If true, the rows will be processed when parsed, in the endTag call.
	 */
	protected $_doProcess = false;

	/**
	 * Boolean If true, no data will be saved in _result.
	 * If set at false, memory issues might happen with huge data amount.
	 */
	protected $_ephemeralMode = false;

	/**
	 * Options bits
	 */
	const OPTION_RENDER = 0x1;
	const OPTION_PROCESS = 0x2;
	const OPTION_EPHEMERAL = 0x4;

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
		xml_set_element_handler($this->_parser, "startTag", "endTagWrapper");
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
	 * Method to diplay a row.
	 */
	public static function renderRow($row)
	{}

	/**
	 * Method to process a row (eg. insert it in a database).
	 */
	public static function processRow($row)
	{}

	/**
	 * This method calls endTag method and then renderRow if _doRender is set
	 * to true.
	 *
	 * @param $parser resource used to parse the file
	 * @param $name name of the current tag
	 * @param $parser tag's attributes
	 */
	public function endTagWrapper($parser, $name, $attribs = array())
	{
		$row = $this->endTag($parser, $name, $attribs = array());
		if ($row !== null && $this->_doRender) {
			static::renderRow($row);
		}
		if ($row !== null && $this->_doProcess) {
			static::processRow($row);
		}
		if ($row !== null && !$this->_ephemeralMode) {
			$this->_result[] = $row;
		}
	}

	/**
	 * Method which will parse the xml file and set the result in a class
	 * attribute.
	 */
	public function parse($options = 0)
	{
		$this->_doRender = $options & self::OPTION_RENDER == self::OPTION_RENDER;
		$this->_doProcess = $options & self::OPTION_PROCESS == self::OPTION_PROCESS;
		$this->_ephemeralMode = $options & self::OPTION_EPHEMERAL == self::OPTION_EPHEMERAL;

		$fh = @fopen($this->_file, "r");
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
