<?php

abstract class Model_Type_Abstract
{
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getAdminTable()
	static protected $_adminTable;
	// Tourney data = db data field (a string in key:value;key:value; form)
	protected $_data;
	// Tourney data represented as a TourneyData object for ease of access
	protected $_dataObject;
	// Dirty flag, set to true if options are changed, false when built or loaded
	protected $_dirty = true;
	// The database id of the tourney, 0 for a new object before it is saved to the database
	protected $_id = 0;
	// Match list
	protected $_matchList;
	// Tourney name = db name field
	protected $_name;
	// Participant list
	protected $_participantList;
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
	/**
	 * Build the tournament, only run if $_dirty.  Clears the match list and builds a new one
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
	 * Add a participant to the tourney
	 * @param Model_Participant $participant The participant to add
	 * @return $this
	 */	
	public function addParticipant(Model_Participant $participant)
	{
		$this->_participantList->addParticipant($participant);
		return $this;
	}
	
	/**
	 * Add a list of participants to the tourney
	 * @param Model_ParticipantList $participants The participant list to add
	 * @return $this
	 */	
	public function addParticipant(Model_ParticipantList $participants)
	{
		$this->_participantList->addParticipant($participants);
		return $this;
	}
	
	/**
	 * Gets the match list
	 * @return Model_MatchList
	 */
	public function getMatchList()
	{
		return $this->_matchList;
	}
	
	/**
	 * Gets the local participant list
	 * @return Model_ParticipantList
	 */
	public function getParticipantList()
	{
		return $this->_participantList;
	}
	
	/**
	 * Returns a full list of available tourney types in an array.  The key is the class name and the data is the result of getTypeName()
	 * @return array
	 */
	static public function getTypeList()
	{
		// @todo write getTypeList
	}
	
	/**
	 * Gets the name of the tourney type, used for output
	 * @return String
	 */
	abstract public function getTypeName();
	
	/**
	 * Loads the tourney from the database
	 * @param $index Index of the tournament
	 */
	public function load($index)
	{
		// @todo write load
		/*
		 * Loads the data for the tourney from the database.
		 * First loads basic tourney info from the tourney database.
		 * Then loads all matches for the tourney.  
		 */
	}
	
	/**
	 * Constructor to create an empty tourney
	 */
	function Model_Type_Abstract()
	{
		$this->_matchList = new Model_MatchList();
		$this->_participantList = new Model_ParticipantList();
	}
	
	/**
	 * Constructor to load a tourney from the database
	 * @param $index Index of tourney to load
	 */
	function Model_Type_Abstract($index)
	{
		$this->load($index);
	}

	/**
	 * Remove a participant from the tourney
	 * @param Model_Participant $participant The participant to remove
	 * @return $this
	 */
	public function removeParticipant(Model_Participant $participant)
	{
		$this->_participantList->removeParticipant($participant);
		return $this;
	}
	
	/**
	 * Remove a list of participants from the tourney
	 * @param Model_ParticipantList $participants The participant list to remove
	 * @return $this
	 */
	public function removeParticipant(Model_ParticipantList $participants)
	{
		$this->_participantList->removeParticipant($participants);
		return $this;
	}
	
	/**
	 * Saves the tourney to the database
	 */
	public function save()
	{
		// @todo write save
		/*
		 * Saves the tourney to the database
		 * This should all be done as a transaction, so if there is an error it can roll back the changes
		 */
	}
}
