<?php

class Model_Participant
{
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
	/**
	 * Singleton method to get the participant table class
	 * @return Model_DbTable_Participant
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Participant();
		}
		return $this->_table;
	}
}