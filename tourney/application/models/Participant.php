<?php

class Model_Participant
{
	// Raw data for participant = db data column
	protected $_data;
	// Raw data loaded into Model_TourneyData object
	protected $_dataObject;
	// draw = db draw column
	protected $_draw;
	// id = db id column
	protected $_id;
	// Owner match id = db matchid column
	protected $_matchid;
	// id of actual participant from user or team table = db participantid column
	protected $_participantid;
	// result as a number = db result column
	protected $_result;
	// score = db score column
	protected $_score;
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	// type of participant (Model_User or Model_Team) = db type column
	protected $_type;
	
	public function __clone() {
		$this->_dataObject = clone($this->_dataObject);
	}
	
	public function __toString()
	{
		if ($this->_type && $this->_participantid) {
			$participant = new $this->_type;
			if ($participant instanceof Model_Interface_Participant) {
				$participant->load($this->_participantid);
				return (string) $participant;				
			}
		} elseif ($this->_dataObject['source']) {
			if ($this->_dataObject['source'] instanceof Model_Match) {
				$match = $this->_dataObject['source'];
			} else {
				$match = new Model_Match($this->_dataObject['source']);
			}
			return ucwords($this->_dataObject['sourcetype']) . " $match";
		} else {
			return ".";
		}
	}
	
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
	
	public function clearId()
	{
		$this->_id = NULL;
		return $this;
	}
	
	/**
	 * Gets the data at the offset
	 * @param $offset Offset to get data from
	 * @return mixed
	 */
	public function getData($offset)
	{
		return $this->_dataObject[$offset];
	}
	
	/**
	 * Returns the draw value of this participant.  0 means no draw
	 * @return float
	 */
	public function getDraw()
	{
		return $this->_draw;
	}
	
	/**
	 * Gets the id of this participant
	 * @return integer
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Gets the matchid for this participant
	 * @return integer
	 */
	public function getMatchid()
	{
		return $this->_matchid;
	}
	
	/**
	 * Gets the match object for this participant
	 * @return Model_Match
	 */
	public function getMatch()
	{
		if (isset($this->_matchid)) {
			return new Model_Match($this->_matchid);
		}
		return NULL;
	}
	
	/**
	 * Gets the participant id
	 * @return integer
	 */
	public function getParticipantid()
	{
		return $this->_participantid;
	}
	
	/**
	 * Gets the actual participant object
	 * @return mixed
	 */
	public function getParticipant()
	{
		if (isset($this->_type)) {
			if (!class_exists($this->_type)) {
				throw new Exception('Participant type class does not exist');
			}
			$returnobj = new $this->_type;
			if ($returnobj instanceof Model_Interface_Participant) {
				$returnobj->load($this->_participantid);
				return $returnobj;
			}
		}
		return NULL;
	}
	
	/**
	 * Gets the result for this participant, 0 if no result yet
	 * @return integer
	 */
	public function getResult()
	{
		return (integer) $this->_result;
	}
	
	/**
	 * Gets the score for this participant
	 * @return mixed
	 */
	public function getScore()
	{
		return $this->_score;
	}
	
	/**
	 * Gets the object type of the participant
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}
	
	/**
	 * True if there is a result
	 * @return boolean
	 */
	public function hasResult()
	{
		return ($this->_result > 0);
	}
	
	/**
	 * Loads the participant from the database
	 * @param $index Index of participant to load
	 * @return $this
	 */
	public function load($index)
	{
		/*
		 * A simple load, which will just load the relevant data from the database and populate this object with it
		 * The participant table has a data column, and the data from that column should be loaded into the $_dataObject using load
		 */
		$select = $this->_getTable()->select()->where('id = ?', $index);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if (!$result) {
			throw new Exception("participant id $index not found");
		}
		$this->_id = $result['id'];
		$this->_matchid = $result['matchid'];
		$this->_participantid = $result['participantid'];
		$this->_type = $result['type'];
		$this->_score = $result['score'];
		$this->_result = $result['result'];
		$this->_draw = $result['draw'];
		$this->_data = $result['data'];
		$this->_dataObject->add($this->_data);
		
		return $this;
	}
	
	function Model_Participant($index = 0)
	{
		$this->_dataObject = new Model_TourneyData();
		if ($index) {
			$this->load($index);
		}
	}
	
	/**
	 * Save this participant to the database
	 * @return $this
	 */
	public function save()
	{
		/*
		 * A simple save, but should check that there is a matchid before it is saved
		 * No point in having a participant without a parent match for it to participate in
		 * Throw exceptions if it is missing required data, don't want to be inserting corrupt stuff into the database
		 * If there is an $_id already that means it is already in the database, so just use update
		 * Otherwise, use insert
		 */
		// Make sure username is set
		if (!$this->_matchid) {
			throw new Exception('Unable to save participant as no matchid specified' . Zend_Debug::dump($this, NULL, false));
		}
		$data = array(
			'matchid' => (integer) $this->_matchid,
			'participantid' => (string) $this->_participantid,
			'type' => (string) $this->_type,
			'score' => (float) $this->_score,
			'result' => (integer) $this->_result,
			'draw' => (integer) $this->_draw,
			'data' => (string) $this->_dataObject,
		);
		$select = $this->_getTable()->select()->where('id = ?', (integer) $this->_id);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if ($result) {
			// There is a row, so just update
			$this->_getTable()->update($data, $this->_getTable()->getAdapter()->quoteInto('id = ?', $this->_id));
		} else {
			// There is no row, so insert
			$this->_getTable()->insert($data);
		}
		return $this;
	}
	
	/**
	 * Set the participant for this object
	 * @param Model_Interface_Participant $participant The participant to set
	 * @return $this
	 */
	public function set(Model_Interface_Participant $participant)
	{
		if (!($this->_participantid = $participant->getId())) {
			throw new Exception('No participant id returned');
		}
		$this->_type = get_class($participant);
		return $this;
	}
	
	/**
	 * Sets data at an offset in the data object
	 * @param $offset Offset to set data at
	 * @param $value Value to set
	 * @return $this
	 */
	public function setData($offset, $value)
	{
		$this->_dataObject[$offset] = $value;
		return $this;
	}
	
	public function setDraw($value)
	{
		if (!is_numeric($value)) {
			throw new Exception('setDraw called with non numeric value');
		}
		$this->_draw = $value;
	}

	/**
	 * Sets the match id for this participant
	 * @param $value Id to set
	 * @return $this
	 */
	public function setMatchid($value)
	{
		$this->_matchid = $value;
		return $this;
	}
	
	/**
	 * Sets the result for this participant
	 * @param $value Integer result
	 * @return $this
	 */
	public function setResult($value)
	{
		$this->_result = (integer) $value;
		return $this;
	}
	
	/**
	 * Sets the score for the participant
	 * @param $value Score to set
	 * @return $this
	 */
	public function setScore($value)
	{
		$this->_score = $value;
		return $this;
	}
	
	/**
	 * Unsets data at an offset
	 * @param $offset Offset to unset at
	 * @return $this
	 */
	public function unsetData($offset)
	{
		unset($this->_dataObject[$offset]);
		return $this;
	}
}
