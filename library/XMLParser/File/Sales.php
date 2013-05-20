<?php

include_once("XMLParser/File.php");

class XMLParser_File_Sales extends XmlParser_File
{
	protected $_currentTag;
	protected $_currentSale;

	public function startTag($parser, $name, $attribs)
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
			|| empty($this->_currentTag)
		) {
			return;
		}

		$this->_currentSale[strtolower($this->_currentTag)] = $content;
	}

	public function endTag($parser, $name, $attribs)
	{
		if ($name == 'SALE') {
			$this->_currentSale['commission'] = number_format(
				50 + $this->_currentSale['amount'] * .05,
				2
			);
			$this->_result[] = $this->_currentSale;
		}

		$this->_currentTag = null;
	}
}
