<?php

class Model_Game
{
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