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