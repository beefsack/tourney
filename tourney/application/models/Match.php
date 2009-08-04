<?php

class Model_Match implements Model_Interface_Unique
{
	// Match data = db data column
	protected $_data;
	// Match data in TourneyData form
	protected $_dataObject;
	// Match gameid = db gameid column
	protected $_gameid;
	// Match id = db id column
	protected $_id;
	// Match participant list for who is playing the match
	protected $_participantList;
	// Match playtime = db playtime column.  Zend_Date object http://zendframework.com/manual/en/zend.date.html
	protected $_playtime;
	// Match scheduletime = db scheduletime column.  Zend_Date object http://zendframework.com/manual/en/zend.date.html
	protected $_scheduletime;
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	// Parent tournament tourneyid = db tourneyid column
	protected $_tourneyid;

	public function __toString()
	{
		$str = "";
		if (isset($this->_id)) {
			$url = Zend_View_Helper_Url::url(
			array('controller' => 'match',
					'action' => 'view',
					'id' => $this->_id,
			), NULL, true);
			//$str .= "<a href=\"" . $url . "\">#" . $this->_id . "</a>";
			$str .= $this->_id;
		}
		return $str;
	}

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
	 * Finds any participants that source themselves from this match, and if there is a result here update them
	 */
	protected function _updateDependants()
	{
		if ($this->hasResult()) {
			foreach ($this->_participantList as $p) {
				if ($p instanceof Model_Participant) {
					if ($p->getResult() == 1) {
						$winner = $p;
					} elseif (!$loser) {
						$loser = $p;
					} else {
						if ($loser->getResult() < $p->getResult()) {
							$loser = $p;
						}
					}
				}
			}
			$table = new Model_DbTable_Participant();
			$select = $table->select()
				->where('data like ?', '%source:' . $this->_id . ';%');
			$stmt = $select->query();
			$result = $stmt->fetchAll();
			foreach ($result as $row) {
				$p = new Model_Participant($row['id']);
				if ($p->getData('sourcetype') == 'winner') {
					$p->set($winner->getParticipant());
				} elseif ($p->getData('sourcetype') == 'loser') {
					$p->set($loser->getParticipant());
				}
				$p->save();
				$submatch = $p->getMatch()->_updateDependants();
			}
		}
	}

	/**
	 * Gets the standings from the victory condition object, then sets the participants draw and result values
	 * This works really easily because of pass by reference.  The participants passed are the same as the participants returned in the return array.
	 */
	protected function _updateResult()
	{
		if ($this->hasResult()) {
			$scoringtype = $this->getGame()->getScoringtype();
			if (!class_exists($scoringtype)) {
				throw new Exception("$scoringtype class does not exist");
			}
			$scoreobj = new $scoringtype;
			if ($scoreobj instanceof Model_VictoryCondition_Abstract) {
				$results = $scoreobj->getStandings($this->_participantList);
				foreach ($results as $row) {
					$p = $row['participant'];
					if ($p instanceof Model_Participant) {
						$p->setResult($row['result']);
						$p->setDraw($row['draw']);
					} else {
						throw new Exception("found non Model_Participant in returned standings from $scoringtype");
					}
				}
			} else {
				throw new Exception("$scoringtype is not an instance of Model_VictoryCondition_Abstract");
			}
		}
	}
	
	/**
	 * Adds a participant to the match
	 * @param $participant Participant to add
	 * @return $this
	 */
	public function addParticipant($participant)
	{
		$this->_participantList->addParticipant($participant);
		return $this;
	}

	/**
	 * Get an item from the dataObject
	 * @param $offset
	 * @return mixed
	 */
	public function getData($offset)
	{
		return $this->_dataObject[$offset];
	}
	
	public function getForm()
	{
		$form = new Zend_Dojo_Form();
		
		$form->setMethod('post');
		
		$element = new Zend_Dojo_Form_Element_DateTextBox('playdate');
		$element->setLabel('Play Date');
		$element->setRequired(true);
		$form->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_TimeTextBox('playtime');
		$element->setLabel('Play Time');
		$element->setRequired(true);
		$form->addElement($element);
		
		foreach ($this->_participantList as $p) {
			$element = new Zend_Dojo_Form_Element_NumberTextBox($p->getId().'score');
			$element->setLabel('Score for ' . $p->getParticipantid());
			$element->setRequired(true);
			$form->addElement($element);
		}
		
		$element = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$element->setLabel('Submit');
		$form->addElement($element);
		
		return $form;
	}

	/**
	 * Gets the game object for this match
	 * @return Model_Game
	 */
	public function getGame()
	{
		if (isset($this->_gameid)) {
			return new Model_Game($this->_gameid);
		}
		return NULL;
	}

	/**
	 * Returns the gameid
	 * @return integer
	 */
	public function getGameid()
	{
		return $this->_gameid;
	}

	/**
	 * Gets the match id, 0 if not saved in db yet
	 * @return integer
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Gets the participant list
	 * @return Model_ParticipantList
	 */
	public function getParticipantList()
	{
		return $this->_participantList;
	}

	/**
	 * Gets the play time, empty if not played yet
	 * @return string
	 */
	public function getPlaytime()
	{
		if ($this->_playtime->getTimestamp() == 0) {
			return NULL;
		}
		return $this->_playtime->get();
	}

	/**
	 * Gets the scores from each of the participants in the participant list, checks the game object to get the requirement for victory, then returns a ParticipantList in the order of placings.
	 * @return Model_ParticipantList, NULL if no result yet because playtime is not set
	 */
	public function getResult()
	{
		if ($this->_scheduletime->getTimestamp() > 0 && ($game = $this->getGame()) !== NULL) {
			$scoringobj = new $game->getScoringtype();
			if ($scoringobj instanceof Model_VictoryCondition_Abstract) {
				return $scoringobj->getStandings($this->_participantList);
			}
		}
		return NULL;
	}

	/**
	 * Gets the scheduled time, NULL if there is no scheduled time
	 * @return string
	 */
	public function getScheduletime()
	{
		if ($this->_scheduletime->getTimestamp() == 0) {
			return NULL;
		}
		return $this->_scheduletime->get();
	}

	/**
	 * Gets the parent tourney id, 0 if it is a standalone match
	 * @return integer
	 */
	public function getTourneyid()
	{
		return $this->_tourneyid;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Interface/Model_Interface_Unique#getUniqueId()
	 */
	public function getUniqueId()
	{
		return $this->_id;
	}

	public function handleForm(array $data)
	{
		$this->setPlaytime(time());
		foreach ($this->_participantList as $p) {
			$p->setScore($data[$p->getId() . 'score']);
		}
	}
	
	public function hasResult()
	{
		return ($this->_playtime->getTimestamp() > 0);
	}

	/**
	 * Load a match into this object from the database
	 * @param $index Index of match to load
	 * @return $this
	 */
	public function load($id)
	{
		/*
		 * Loads this match in from the database
		 * will load the match with the specified index
		 * if it can't find a row in the table with that index, it should throw a new exception
		 * after loading the match successfully, it will need to load the participants
		 * for loading the participants, the Model_ParticipantList has a method for this.  You can call load and pass the id of this match
		 */

		$select = $this->_getTable()->select()->where('id = ?', $id);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if (!$result) {
			throw new Exception("id '$id' not found");
		}
		$this->_id = $result['id'];
		$this->_tourneyid = $result['tourneyid'];
		$this->_gameid = $result['gameid'];
		$this->_scheduletime->set($result['scheduletime']);
		$this->_playtime->set($result['playtime']);
		$this->_data = $result['data'];
		$this->_dataObject->add($this->_data);

		$this->_participantList->load($this->_id);

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
		$this->_playtime = new Zend_Date(0);
		$this->_scheduletime = new Zend_Date(0);
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
		/*
		 * A bit more to this save than just saving the match, this also needs to save all the participants for this match
		 * Firstly it needs to save the match because if it doesn't have an $_id yet it needs to get one.
		 * Each participant needs to know the match $_id before they can be saved
		 * After saving the match to the database, you can get the last inserted id using $this->_getTable()->getAdapter()->lastInsertId();
		 */
		if (!$this->_gameid) {
			throw new Exception('Unable to save match as no gameid specified');
		}
		$data = array(
			'tourneyid' => (integer) $this->_tourneyid,
			'gameid' => (integer) $this->_gameid,
			'scheduletime' => (string) $this->_scheduletime,
			'playtime' => (string) $this->_playtime,
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
			
		// Now save the participants below this match.
		// Care is taken to first check that the participants don't have a different matchid as the participant may be referenced in many matches
		foreach ($this->_participantList as $p) {
			if ($p instanceof Model_Participant) {
				if ($p->getMatchid() && $p->getMatchid() != $this->_id) {
					// There is a participant belonging to this match that has a different matchid, so we copy it so we don't stuff up the original
					$this->_participantList->removeParticipant($p);
					$newp = clone($p);
					$newp->setMatchid($this->_id);
					$this->_participantList->addParticipant($newp);
				} else {
					$p->setMatchid($this->_id);
				}
			}
		}
		
		// Now update the results if this match has a result
		if ($this->hasResult()) {
			$this->_updateResult();
		}
		
		// And then save them all
		$this->_participantList->save();
		
		// If there is a result, we should make sure dependants are updated too
		if ($this->hasResult()) {
			$this->_updateDependants();
		}
			
		return $this;
	}

	/**
	 * Sets a piece of data in the dataObject
	 * @param $offset Offset to save at
	 * @param $value Value to save
	 * @return $this
	 */
	public function setData($offset, $value)
	{
		$this->_dataObject[$offset] = $value;
		return $this;
	}

	/**
	 * Sets the game for the match.  Uses a reference so if the game has to be saved and is used for multiple matches, it is only saved once.  Use getGame and unset to prevent memory leaks.
	 * @param Model_Game $game The game to set
	 * @return $this
	 */
	public function setGame($game)
	{
		if ($game instanceof Model_Game) {
			$this->_gameid = $game->getId();
		} else {
			$this->_gameid = $game;
		}
		return $this;
	}

	/**
	 * Sets the play time.  Setting this will flag the game as finished and will produce a result.
	 * @param $value Any accepted value for Zend_Date
	 * @return $this
	 */
	public function setPlaytime($value)
	{
		$this->_playtime->set($value);
		return $this;
	}

	/**
	 * Sets the schedule time.
	 * @param $value Any accepted value for Zend_Date
	 * @return $this
	 */
	public function setScheduletime($value)
	{
		$this->_scheduletime->set($value);
		return $this;
	}

	/**
	 * Sets the parent tourney id
	 * @param $value Tourney id
	 * @return $this
	 */
	public function setTourneyid($value)
	{
		$this->_tourneyid = $value;
		return $this;
	}

	/**
	 * Unset a piece of data in the data object
	 * @param $offset Offset to unset
	 * @return $this
	 */
	public function unsetData($offset)
	{
		unset($this->_dataObject[$offset]);
		return $this;
	}
}
