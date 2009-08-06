<?php

abstract class Model_Type_Abstract implements Model_Interface_Unique
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
		if (!isset(self::$_table)) {
			self::$_table = new Model_DbTable_Tourney();
		}
		return self::$_table;
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
	 * Returns a subform to be included in the main tourney form
	 * @param Zend_Form_Subform $form
	 */
	protected function _typeSpecificGetForm(Zend_Form_Subform $form) { return NULL; }
	
	/**
	 * Handles the data in the form specific to the tourney type
	 * @param array $data The data returned from the submission of the form
	 */
	protected function _typeSpecificHandleForm(array $data) {}
	
	/**
	 * Saves the match list
	 */
	protected function _saveMatches()
	{
		if (!$this->_id) {
			throw new Exception("Unable to save tournament matches, no id found");
		}
		foreach ($this->_matchList as $m) {
			if ($m instanceof Model_Match) {
				if ($m->getTourneyid() && $m->getTourneyid() != $this->_id) {
					// There is a match belonging to this tourney that has a different tourneyid, so we copy it so we don't stuff up the original
					$this->_matchList->removeMatch($m);
					$newm = clone($m);
					$newm->setTourneyid($this->_id);
					$this->_matchList->addMatch($newm);
				} else {
					$m->setTourneyid($this->_id);
				}
			}
		}
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
		$select = self::_getTable()->select()->where('id = ?', $index);
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
	 * Gets a form for creation of a tourney of this type
	 * @return Zend_Form
	 */
	public function getForm() {
		// Make the form to return
		$form = new Zend_Form();
		$form->setAttrib('onSubmit', 'return checkPlayers()');
		
		// Add the hidden field to hold the serialized version if it exists
		$element = new Zend_Form_Element_Hidden('object');
		if ($this->_matchList->count() > 0) {
			$serial = serialize($this);
			//$element->setValue($serial);
			$element->setValue('egg');
		}
		$form->addElement($element);
		
		// Subform for general options which every tournament has
		$generalsubform = new Zend_Form_SubForm();
		$generalsubform->setLegend('Tournament Options');
		
		$element = new Zend_Dojo_Form_Element_TextBox('name');
		$element->setLabel('Name');
		$element->setRequired(true);
		$element->addValidator(new Zend_Validate_StringLength(5));
		$generalsubform->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_FilteringSelect('game');
		$element->setLabel('Game');
		$element->setRequired(true);
		$element->setStoreId('gameStore');
		$element->setStoreType('dojox.data.QueryReadStore');
		$element->setStoreParams(array(
			'url' => PUBLIC_PATH . '/ajax/games/format/ajax',
		));		
		$generalsubform->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_FilteringSelect('playerselect');
		$element->setLabel('Add Player');
		$element->setStoreId('playerStore');
		$element->setStoreType('dojox.data.QueryReadStore');
		$element->setStoreParams(array(
			'url' => PUBLIC_PATH . '/ajax/players/format/ajax',
		));
		$generalsubform->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_Button('addplayer');
		$element->setLabel('Add Player');
		$element->setAttrib('onClick', 'addPlayer(dijit.byId("generalsubform-playerselect"), document.getElementById("fieldset-playersubform"))');
		$generalsubform->addElement($element);
		
		$playersubform = new Zend_Form_SubForm();
		$playersubform->setLegend('Players');
		$playersubform->setName('players');
		
		$generalsubform->addSubForm($playersubform, 'playersubform');
		
		$form->addSubForm($generalsubform, 'generalsubform');
		
		// Let the tourney type add more fields if it has specific options
		$typesubform = new Zend_Form_SubForm();
		$typesubform->setLegend($this->getTypeName() . ' Options');
		$this->_typeSpecificGetForm($typesubform);
		if ($typesubform->count() > 0) {
			$form->addSubForm($typesubform, 'typesubform');
		}
		
		// Add submit buttons
/*		$element = new Zend_Dojo_Form_Element_SubmitButton('preview');
		$element->setLabel('Preview Tournament');
		$form->addElement($element);*/
		
		$element = new Zend_Dojo_Form_Element_SubmitButton('save');
		$element->setLabel('Save Tournament');
/*		if ($this->_matchList->count() == 0) {
			$element->setAttrib('disabled', 'disabled');
		}*/
		$form->addElement($element);
		// Return the form
		return $form;
	}
	
	/**
	 * Gets the game for the tourney
	 * @return Model_Game
	 */
	public function getGame()
	{
		if ($this->_dataObject['gameid'] > 0) {
			return new Model_Game($this->_dataObject['gameid']);
		}
		return NULL;
	}
	
	/**
	 * Gets the id of this tournament
	 * @return integer
	 */
	public function getId()
	{
		return $this->_id;
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
	 * (non-PHPdoc)
	 * @see models/Interface/Model_Interface_Unique#getUniqueId()
	 */
	public function getUniqueId()
	{
		return $this->_id;
	}
	
	/**
	 * Takes data returned from the form and adds it to this object
	 * @param array $data Data returned from the form
	 */
	public function handleForm(array $data)
	{
		// Set up local fields
		
		// Set the name
		$this->_name = $data['generalsubform']['name'];
		
		// Set the game
		$this->setGame(new Model_Game($data['generalsubform']['game']));
		
		// Add all the participants
		foreach ($data['player'] as $player) {
			$participant = new Model_Participant();
			$user = new Model_User($player);
			$participant->set($user);
			$this->addParticipant($participant);
		}		
		
		// Pass the data to the type specific handler for any extra data
		if (is_array($data['typesubform'])) {
			$this->_typeSpecificHandleForm($data['typesubform']);
		}
	}
	
	/**
	 * Loads the tourney from the database
	 * @param $index Index of the tournament
	 * @return $this
	 */
	public function load($index)
	{
		/*
		 * Loads the data for the tourney from the database.
		 * First loads basic tourney info from the tourney database.
		 * See the user class for how to fetch using Zend_Db (user load has been written already)
		 * Then loads all matches for the tourney by making a new Model_Match passing the match id to the constructor.  The loaded match can then be added to the matchlist
		 */
		$select = $this->_getTable()->select()->where('id = ?', $index);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if (!$result) {
			throw new Exception("tourney id '$index' not found");
		}
		$this->_id = $result['id'];
		$this->_name = $result['name'];
		$this->_data = $result['data'];
		$this->_dataObject->add($this->_data);
		
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
		/*
		 * Saves the tourney to the database
		 * This should all be done as a transaction, so if there is an error it can roll back the changes
		 * Once the tourney has been rebuilt, it should save the data to the database.
		 * If $_id was set before save, this tourney is already in the database an only needs to be updated
		 * If $_id is 0, it needs to be an insert not an update
		 * The matches aren't saved in this method because certain types of tourneys will have to override it to do it differently
		 * Because of this, another function called saveMatches() is called and tourneys can override it if neccessary
		 */
		$adapter = $this->_getTable()->getAdapter();
		$adapter->beginTransaction();
		try {
			$data = array(
				'type' => (string) get_class($this),
				'name' => (string) $this->_name,
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
				$this->_id = $this->_getTable()->getAdapter()->lastInsertId();
			}
			
			$this->_saveMatches();
			
			// now commit all of the changes
			$adapter->commit();
		} catch (Exception $e) {
			$adapter->rollBack();
			throw $e;
		}
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
