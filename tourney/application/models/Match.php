<?php

class Model_Match
{
	static protected $_table;
	
	/**
	 * Singleton method to get the match table class
	 * @return Model_DbTable_Match
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Match();
		}
		return $this->_table;
	}
}