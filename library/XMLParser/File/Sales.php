<?php

include_once("XMLParser/File.php");

class XMLParser_File_Sales extends XmlParser_File
{
	protected $_currentTag;
	protected $_currentSale;

	protected $_sale_columns = array(
		'affiliate', 'amount', 'datetime', 'orderref'
	);

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

	public function tagContent($parser, $content)
	{
		if (
			in_array($this->_currentTag, array('SALES', 'SALE'))
			|| !in_array(strtolower($this->_currentTag), $this->_sale_columns)
			|| empty($this->_currentTag)
		) {
			return;
		}

		$this->_currentSale[strtolower($this->_currentTag)] = $content;
	}

	public function endTag($parser, $name, $attribs = array())
	{
		$return = null;
		if ($name == 'SALE') {
			$this->_currentSale['commission'] = number_format(
				50 + $this->_currentSale['amount'] * .05,
				2
			);
			$return = $this->_currentSale;
		}

		$this->_currentTag = null;
		return $return;
	}
}
