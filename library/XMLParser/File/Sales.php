<?php

include_once("XMLParser/File.php");

class XMLParser_File_Sales extends XmlParser_File
{
	/**
	 * String - Name of the current tag parsed
	 */
	protected $_currentTag;

	/**
	 * Array - Content of the current sale parsed
	 */
	protected $_currentSale;

	protected $_sale_columns = array(
		'affiliate', 'amount', 'datetime', 'orderref'
	);

	/**
	 * Implementation of the startTag method, called in the beginning of a
	 * tag parsing.
	 */
	public function startTag($parser, $name, $attribs = array())
	{
		if ($name == "SALES") {
			$this->_result = array();
		}
		else if ($name == "SALE") {
			$this->_currentSale = array();
		}

		$this->_currentTag = $name;
	}

	/**
	 * Implementation of the tagContent method, save the content of each row in
	 * an array
	 */
	public function tagContent($parser, $content)
	{
		if (
			in_array($this->_currentTag, array('SALES', 'SALE'))
			|| !in_array(strtolower($this->_currentTag), $this->_sale_columns)
			//empty strings between tags (new line char for example)
			|| empty($this->_currentTag)
		) {
			return;
		}

		$this->_currentSale[strtolower($this->_currentTag)] = $content;
	}

	/**
	 * Implementation of the endTag method, called in the end of a
	 * tag parsing.
	 */
	public function endTag($parser, $name, $attribs = array())
	{
		$return = null;
		if ($name == 'SALE') {
			//make sure the row is complete
			//by merging an empty "template" of the row
			$this->_currentSale = array_merge(
				array_fill_keys($this->_sale_columns, ''),
				$this->_currentSale
			);

			$this->_currentSale['commission'] = 50 + $this->_currentSale['amount'] * .05;

			$return = $this->_currentSale;
		}

		$this->_currentTag = null;
		return $return;
	}

	/**
	 * Method to display a row. It'll be automatically called for each row
	 * during the parse if the parse method is called with the argument
	 * $renderOnTheFly set to true.
	 */
	public static function renderRow($row)
	{
		printf(
			"%s|%0.2f|%0.2f|%s|\"%s\"\n",
			$row['affiliate'],
			$row['amount'],
			$row['commission'],
			strtotime($row['datetime']),
			$row['orderref']
		);
	}
}
