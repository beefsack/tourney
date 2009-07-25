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
	 * Build the tournament, should only run if $_dirty.  Clears the match list and builds a new one
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
	 * Loads the match list
	 */
	protected function _loadMatches()
	{
		$this->_matchList->load($this->_id);
	}
	
	/**
	 * Saves the match list
	 */
	protected function _saveMatches()
	{
		$this->_matchList->save();
	}
	
	/**
	 * Add a participant
	 * @param $participant Participant to add
	 * @return $this
	 */
	public function addParticipant($participant)
	{
		$this->_participantList->addParticipant($participant);
		$this->_dirty = true;
		return $this;
	}
	
	static public function factory($index)
	{
		/*
		 * factory finds out what sort of tournament has an id of $index, then creates a new tournament of that type and loads it
		 */
		$select = $this->_getTable()->select()->where('id = ?', $index);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if (!$result) {
			throw new Exception("Unable to find tournament with id $index");
		}
		if (class_exists($result['type'])) {
			$tourney = new $result['type'];
			if ($tourney instanceof Model_Type_Abstract) {
				$tourney->load($index);
			} else {
				throw new Exception("Class not instance of Model_Type_Abstract");
			}
		} else {
			throw new Exception("Class not found " . $result['type']);
		}
		return $tourney;
	}
	
	/**
	 * Gets the game for the tourney
	 * @return Model_Game
	 */
	public function getGame()
	{
		if ($this->_gameid > 0) {
			return new Model_Game($this->_gameid);
		}
		return NULL;
	}
	
	/**
	 * Gets the match list
	 * @return Model_MatchList
	 */
	public function getMatchList()
	{
		$this->_buildTourney();
		return $this->_matchList;
	}

	/**
	 * Returns a full list of available tourney types in an array.  The key is the class name and the data is the result of getTypeName()
	 * @return array
	 */
	static public function getTypeList()
	{
		/*
		 * Searches the Type folder for all different tournament types.
		 * This is used for when a user is creating a tournament and want to select what type to use
		 * The array is set up like so:
		 * key: class name for tourney type (get_class() could be useful)
		 * value: result of object getName()
		 */
		$retarray = array();
		if ($dir = scandir(APPLICATION_PATH . '/models/Type')) {
			foreach ($dir as $file) {
				if ($file != 'Abstract.php' && strtolower(substr($file, strrpos($file, '.') + 1)) == 'php') {
					$classname = 'Model_Type_' . substr($file, 0, strrpos($file, '.'));
					try {
						if (class_exists($classname)) {
							$instance = new $classname;
							if ($instance instanceof Model_Type_Abstract) {
								$retarray[$classname] = $instance->getTypeName();
							}
						}
					} catch (Exception $e) {}
				}
			}
		}
		return $retarray;
	}
	
	/**
	 * Gets the name of the tourney type, used for output
	 * @return String
	 */
	abstract public function getTypeName();
	
	/**
	 * Loads the tourney from the database
	 * @param $index Index of the tournament
	 * @return $this
	 */
	public function load($index)
	{
		// @todo write load
		/*
		 * Loads the data for the tourney from the database.
		 * First loads basic tourney info from the tourney database.
		 * See the user class for how to fetch using Zend_Db (user load has been written already)
		 * Then loads all matches for the tourney by making a new Model_Match passing the match id to the constructor.  The loaded match can then be added to the matchlist
		 */
		$this->_loadMatches();
		$this->_dirty = false; // Since it is a fresh load, it isn't dirty
		return $this;
	}
	
	/**
	 * Constructor to load a tourney from the database
	 * @param $index Index of tourney to load
	 */
	function Model_Type_Abstract($index = 0)
	{
		$this->_matchList = new Model_MatchList();
		$this->_participantList = new Model_ParticipantList();
		$this->_dataObject = new Model_TourneyData();
		if ($index > 0) {
			$this->load($index);
		}
	}

	/**
	 * Saves the tourney to the database
	 * @return $this
	 */
	public function save()
	{
		$this->_buildTourney();
		// @todo write save
		/*
		 * Saves the tourney to the database
		 * This should all be done as a transaction, so if there is an error it can roll back the changes
		 * Once the tourney has been rebuilt, it should save the data to the database.
		 * If $_id was set before save, this tourney is already in the database an only needs to be updated
		 * If $_id is 0, it needs to be an insert not an update
		 * The matches aren't saved in this method because certain types of tourneys will have to override it to do it differently
		 * Because of this, another function called saveMatches() is called and tourneys can override it if neccessary
		 */
		$this->_saveMatches();
		$this->_dirty = false; // It has just been saved, it's not dirty anymore
		return $this;
	}
	
	/**
	 * Sets the game to be played in the tournament
	 * @param $game Game to be played
	 * @return $this
	 */
	public function setGame(Model_Game $game)
	{
		$this->_dataObject['gameid'] = $game->getId();
		$this->_dirty = true;
		return $this;
	}
}
