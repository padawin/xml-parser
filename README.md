# Requirements
Zend Framework is needed. A Zend folder is expected in ./library/
The Zend version used for the development and the tests was the 1.12.3.

# Tests
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

# Parse options
- XMLParser_File::OPTION_RENDER: With this option, the method renderRow($row)
	will be called for each row
- XMLParser_File::OPTION_PROCESS: With this option, the method processRow($row)
	will be called for each row
- XMLParser_File::OPTION_EPHEMERAL: With this option, the rows are not kept
	in memory, so the method getResult() will return no data.

# Notes
- ProcessRow and renderRow could be improved to provide a callback, to be able
	to execute other actions while being in ephemeral mode.
- The XML parse could be improved to raise exceptions or other errors if the XML
	does not contains the needed columns. For the moment, other tags are just
	ignored.
- Some more test could be added concerning the sales informations integrity
	(values format for example) with an errors management (with the number or
	the list of lines in error for example)
