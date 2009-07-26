<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
		$doctypeHelper = new Zend_View_Helper_Doctype();
		$doctypeHelper->doctype('XHTML1_STRICT');
	}
	
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => dirname(__FILE__),
        ));
        return $autoloader;
    }
    
    protected function _initDb()
    {
    	$dbconfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/database.ini', APPLICATION_ENV);
    	$db = Zend_Db::factory($dbconfig);
    	Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }
    
    protected function _initTimezone()
    {
    	date_default_timezone_set('UTC');
    }
}
