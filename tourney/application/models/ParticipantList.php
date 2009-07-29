<?php

class Model_ParticipantList implements Iterator
{
	protected $_list = array();
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
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
	
	/**
	 * Add a Model_Participant or Model_ParticipantList object to the list.  Duplicates are ignored.
	 * @param $participant Model_Participant or Model_ParticipantList to add to the list.
	 * @return $this
	 */
	public function addParticipant($participant)
	{
		/*
		 * This adds a Model_Participant or all the Model_Participants in a Model_ParticipantList to the $_list array of this object
		 * If $participant is a Model_Participant, add it using $_list[]
		 * If $participant is a Model_ParticipantList, loop through each participant in that list and call $this->addParticipant for each one
		 * Class type can be checked like this: if ($participant instanceof Model_Participant) {
		 * If $participant is not a Model_Participant or Model_ParticipantList, a new exception should be thrown
		 */
		if ($participant instanceof Model_Participant) {
			$this->_list[] = $participant;
		} elseif ($participant instanceof Model_ParticipantList) {
			foreach ($participant as $p) {
				$this->addParticipant($p);
			}
		} else {
			throw new Exception("Non Model_Participant or Model_ParticipantList passed to addParticipant");
		}
		return $this;
	}
	
	/**
	 * Returns the number of participants in this list
	 * @return integer
	 */
	public function numParticipants()
	{
		return count($this->_list);
	}
	
	/**
	 * Get the current item when iterating.  Required by Iterator interface.
	 * @return Model_Participant
	 */
	public function current()
	{
		return current($this->_list);
	}
	
	/**
	 * Clear the participant list
	 * @return $this
	 */
	public function clearParticipantList()
	{
		unset($this->_list);
		return $this;
	}
	
	/**
	 * Get the current key when iterating.  Required by Iterator interface.
	 * @return Mixed
	 */
	public function key()
	{
		return key($this->_list);
	}
	
	/**
	 * Load the participants for specified match
	 * @param $index Match id
	 * @return $this
	 */
	public function load($index)
	{
		/*
		 * A bit trickier load, similar to a matchlist load
		 * First there will need to be a query finding all participants in the database with matchid of $index
		 * Once that is found, need to loop through each and create a new Model_Participant object for each loading the relevant participant id
		 * After creating each Model_Participant object and loading it, it is added to the array using addParticipant of this object
		 */
		$query = $this->_getTable()->select()->where('matchid = ?', $index);
		$stmt = $query->query();
		$result = $stmt->fetchAll();
		
		foreach ($result as $row) {
			$p = new Model_Participant($row['id']);
			$this->addParticipant($p);
		}
		
		return $this;
	}
		
	/**
	 * Get the next item when iterating.  Required by Iterator interface.
	 * @return Model_Participant
	 */
	public function next()
	{
		return next($this->_list);
	}
	
	/**
	 * Remove the specified participants from the list, if they exists
	 * @param $participant Model_Participant or Model_ParticipantList to remove from the list.
	 * @return $this
	 */
	public function removeParticipant($participant)
	{
		/*
		 * very similar to removeMatch in Model_MatchList
		 * If $participant is a Model_Participant, loop through $_list and remove participant if found
		 * If $participant is a Model_ParticipantList, call removeParticipant for each item of $participant
		 * If $participant is anything else, throw a new exception
		 * Can check class type using instanceof, ie: if ($participant instanceof Model_Participant) {
		 */
		foreach ($this->_list as $key => $p) {
			if ($p === $participant) {
				unset($this->_list[$key]);
			}
		}
		return $this;
	}
	
	/**
	 * Get the previous item when iterating.  Required by Iterator interface.
	 * @return Model_Participant
	 */
	public function rewind()
	{
		return reset($this->_list);
	}
	
	/**
	 * Loops through each participant and calls save on each one
	 * @return $this
	 */
	public function save()
	{
		/*
		 * A simple loop through all the items in $_list and calling save on each one
		 */
		foreach ($this->_list as $p) {
			if ($p instanceof Model_Participant) {
				$p->save();
			}
		}
		
		return $this;
	}
	
	/**
	 * Check if current item is valid.  Required by Iterator interface.
	 * @return boolean
	 */
	public function valid()
	{
		return $this->current() !== false;
	}
}