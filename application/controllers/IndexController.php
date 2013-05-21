<?php

include_once('XMLParser/File/Sales.php');

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		// action body
		$name = APPLICATION_PATH . '/../storage/data.xml';
		$file = XMLParser_File_Sales::getFile($name);
		//the rows will not be processed, neither displayed while parsed and
		//will be kept in memory to be displayed after, in the view
		$file->parse(0);
		$this->view->data = $file->getResult();
    }


}

