<?php

class Model_Match
{
	// Match data = db data column
	protected $_data;
	// Match data in TourneyData form
	protected $_dataObject;
	// Match gameid = db gameid column
	protected $_gameid;
	// Match game in object form
	protected $_gameObject;
	// Match id = db id column
	protected $_id;
	// Match participant list for who is playing the match
	protected $_participantList;
	// Match playtime = db playtime column
	protected $_playtime;
	// Match scheduletime = db scheduletime column
	protected $_scheduletime;
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	// Match tourneyid = db tourneyid column
	protected $_tourneyid;
	
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
	
	/**
	 * Gets the scores from each of the participants in the participant list, checks the game object to get the requirement for victory, then returns a ParticipantList in the order of placings.
	 * @return Model_ParticipantList, NULL if no result yet
	 */
	public function getResult()
	{
		// @todo write getResult
	}
	
	/**
	 * Load a match into this object from the database
	 * @param $index Index of match to load
	 * @return $this
	 */
	public function load($index)
	{
		// @todo write load
		return $this;
	}
	
	/**
	 * Constructor
	 * @param $index Optional match index to load
	 */
	function Model_Match($index = 0)
	{
		$this->_dataObject = new Model_TourneyData();
		$this->_participantList = new Model_ParticipantList();
		$this->_gameObject = new Model_Game();
		if ($index > 0) {
			$this->load($index);
		}
	}
	
	/**
	 * Save this match to the database
	 * @return $this
	 */
	public function save()
	{
		// @todo write save
		return $this;
	}
}
