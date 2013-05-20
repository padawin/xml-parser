<?php

class XMLParser_File
{
	/**
	 * Method to create a object representing a xml file from the file name.
	 * If no file is found, null is returned, else a XMLParser_File instance
	 * is created
	 *
	 * @param $name string path to the file
	 *
	 * @return XMLParser_File|null
	 */
	public static function getFile($name)
	{
		if (!file_exists($name)) {
			return null;
		}

		return new static;
	}
}
