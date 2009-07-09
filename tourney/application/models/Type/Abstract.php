<?php

abstract class Model_Type_Abstract
{
	static protected $_table;
	
	/**
	 * Singleton method to get the tourney table class
	 * @return Model_DbTable_Tourney
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Tourney();
		}
		return $this->_table;
	}
}