<?php

class Model_Type_Season extends Model_Type_Abstract implements Model_Interface_Tree, Model_Interface_Ladder
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
	 * @see models/Type/Model_Type_Abstract#_typeSpecificForm($form)
	 */
	protected function _typeSpecificForm(Zend_Form $form) {
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Model_Interface_Ladder#getLadder()
	 */
	public function getLadder()
	{
		return new Model_LadderType();
	}

	/**
	 * (non-PHPdoc)
	 * @see models/Model_Interface_Tree#getTree()
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