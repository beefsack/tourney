<?php

class Model_Participant
{
	// Raw data for participant = db data column
	protected $_data;
	// Raw data loaded into Model_TourneyData object
	protected $_dataObject;
	// id = db id column
	protected $_id;
	// Owner match id = db matchid column
	protected $_matchid;
	// id of actual participant from user or team table = db participantid column
	protected $_participantid;
	// score = db score column
	protected $_score;
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	// type of participant (Model_User or Model_Team) = db type column
	protected $_type;
	
	/**
	 * Singleton method to get the participant table class
	 * @return Model_DbTable_Participant
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Participant();
		}
		return $this->_table;
	}
}
