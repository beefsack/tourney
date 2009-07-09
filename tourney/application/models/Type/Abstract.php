<?php

abstract class Model_Type_Abstract
{
	// TourneyAdmin table object
	static protected $_adminTable;
	// Match list
	protected $_matchList;
	// Participant list
	protected $_participantList;
	// Tourney table object
	static protected $_table;
	
	/**
	 * Build the tournament, only run if 
	 */
	abstract protected function _buildTourney();
	
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
	
	/**
	 * Singleton method to get the tourneyadmin table class
	 * @return Model_DbTable_TourneyAdmin
	 */
	protected function _getAdminTable()
	{
		if (!isset($this->_adminTable)) {
			$this->_adminTable = new Model_DbTable_TourneyAdmin();
		}
		return $this->_adminTable;
	}
	
	/**
	 * Constructor to create an empty tourney
	 */
	function Model_Type_Abstract()
	{
		$matchList = new Model_MatchList();
	}
	
	/**
	 * Constructor to load a tourney from the database
	 * @param $index Index of tourney to load
	 */
	function Model_Type_Abstract($index)
	{
		// @todo write Model_Type_Abstract loading constructor
	}
}
