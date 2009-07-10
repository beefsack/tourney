<?php

class Model_Game
{
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
	/**
	 * Singleton method to get the game table class
	 * @return Model_DbTable_Game
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Game();
		}
		return $this->_table;
	}
}