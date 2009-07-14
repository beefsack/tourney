<?php

class Model_ParticipantList implements Iterator
{
	protected $_list = array();
	
	/**
	 * Add a Model_Participant or Model_ParticipantList object to the list.  Duplicates are ignored.
	 * @param $participant Model_Participant or Model_ParticipantList to add to the list.
	 * @return $this
	 */
	public function addParticipant($participant)
	{
		// @todo write addParticipant
		/*
		 * This adds a Model_Participant or all the Model_Participants in a Model_ParticipantList to the $_list array of this object
		 * If $participant is a Model_Participant, first it should check if it already exists in $_list, and if not, add it using $_list[]
		 * If $participant is a Model_ParticipantList, loop through each participant in that list and call $this->addParticipant for each one
		 * Class type can be checked like this: if ($participant instanceof Model_Participant) {
		 * If $participant is not a Model_Participant or Model_ParticipantList, a new exception should be thrown
		 */
		return $this;
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
		// @todo write load
		/*
		 * A bit trickier load, similar to a matchlist load
		 * First there will need to be a query finding all participants in the database with matchid of $index
		 * Once that is found, need to loop through each and create a new Model_Participant object for each loading the relevant participant id
		 * After creating each Model_Participant object and loading it, it is added to the array using addParticipant of this object
		 */
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
		// @todo write removeParticipant
		/*
		 * very similar to removeMatch in Model_MatchList
		 * If $participant is a Model_Participant, loop through $_list and remove participant if found
		 * If $participant is a Model_ParticipantList, call removeParticipant for each item of $participant
		 * If $participant is anything else, throw a new exception
		 * Can check class type using instanceof, ie: if ($participant instanceof Model_Participant) {
		 */
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
		// @todo write save
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