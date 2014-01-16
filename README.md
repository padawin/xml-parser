# XML Parser

A PHP class to parse XML files of any size.

It has been conceived to process data rows stored in XML.

It needs adapter classes according to the XML file to parse.

The classes are in library/XMLParser.

The parser class has some options such as:
- doProcess which will do some
user-defined process for each row (such as storing in a database for example).
Those processes are defined in the adapters.
- doRender which will call a method to render each row. By default the called
method is empty and can be overloaded in an adapter.

## Requirements
The class by itself has no specific dependancy, but Zend Framework is needed to
run the tests and the examples (CLI and web).

A Zend folder is expected in ./library/
The Zend version used for the development and the tests was the 1.12.3.

## Tests
To have fully working unit tests, the file storage/data-unreadable.xml must not
be readable, by doing for example:
	```
	sudo chown root:root storage/data-unreadable.xml
	sudo chmod 640 storage/data-unreadable.xml
	```
To run the unit tests, run the following command in the project root:
	```
	make test
	```

A test script is available in scripts/process-file.php. This script is
executable and needs no option to run.
It'll try to parse the file storage/data-huge.xml

An other example of the parsing tool is also available in the controller
application/controllers/IndexController.php.

## Parse options
- XMLParser_File::OPTION_RENDER: With this option, the method renderRow($row)
	will be called for each row
- XMLParser_File::OPTION_PROCESS: With this option, the method processRow($row)
	will be called for each row
- XMLParser_File::OPTION_EPHEMERAL: With this option, the rows are not kept
	in memory, so the method getResult() will return no data.

## Notes
- ProcessRow and renderRow could be improved to provide a callback, to be able
	to execute other actions while being in ephemeral mode.
- The XML parse could be improved to raise exceptions or other errors if the XML
	does not contains the needed columns. For the moment, other tags are just
	ignored.
- Some more test could be added concerning the sales informations integrity
	(values format for example) with an errors management (with the number or
	the list of lines in error for example)
