<?php

class Model_Type_Season extends Model_Type_Abstract implements Model_Treeable, Model_Ladderable
{
	// The ladder info for this object
	protected $_ladder;
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
	 * @see models/Model_Ladderable#getLadder()
	 */
	public function getLadder()
	{
		return new Model_LadderType();
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
		return "Season";
	}
}