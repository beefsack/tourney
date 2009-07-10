<?php

class Model_User
{
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
	/**
	 * Singleton method to get the user table class
	 * @return Model_DbTable_User
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_User();
		}
		return $this->_table;
	}
}