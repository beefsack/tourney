<?php

class Model_Type_SingleElimination extends Model_Type_Abstract implements Model_Treeable
{
	// The tree for the elimination
	protected $_tree;
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_buildTourney()
	 */
	protected function _buildTourney()
	{
		if ($this->_dirty) {
			$this->_matchList->clearMatchList();
			// Rebuild the tournament from scratch
			$this->_dirty = false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Model_Treeable#getTree()
	 */
	public function getTree()
	{
		return new Model_TreeType();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#getTypeName()
	 */
	public function getTypeName()
	{
		return "Single Elimination";
	}
}