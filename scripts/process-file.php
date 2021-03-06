#!/usr/bin/php
<?php

require('bootstrap.php');

include_once('XMLParser/File/Sales.php');

$name = APPLICATION_PATH . '/../storage/data-huge.xml';
$file = XMLParser_File_Sales::getFile($name);
if (empty($file)) {
	echo "The file $name was not found. Exiting.\n";
}
else {
	//the rows will be displayed while parsed and not kept in memory
	$file->parse(XMLParser_File::OPTION_RENDER | XMLParser_File::OPTION_EPHEMERAL);
}
