<?php

abstract class Model_MatchupType_Abstract
{
	// List of participants
	protected $_participantList;
	
	protected function _getNextPowerOfTwo($value)
	{
		return pow(2, ceil(log($value, 2)));
	}
	
	/**
	 * Adds participants to the participant list
	 * @param $participant A Model_Participant or Model_ParticipantList to add
	 * @return $this
	 */
	public function addParticipant($participant)
	{
		$this->_participantList->addParticipant($participant);
		return $this;
	}
	
	/**
	 * Clear the participant list
	 * @return $this
	 */
	public function clearParticipants()
	{
		$this->_participantList->clearParticipantList();
		return $this;
	}
	
	/**
	 * Returns a multidimensional array of pairings of players
	 * @return array
	 */
	abstract public function getMatchups();
	
	/**
	 * Returns an array containing the full list of Model_MatchupTypes available.  The key is the class name, and the data is the value returned by getName().
	 * @return array
	 */
	static public function getMatchupTypeList()
	{
		// @todo write getMatchupTypeList
		/*
		 * What this will do is search through the type folder finding all the Abstract subclasses, and return an array list of them
		 * This will make it possible to make a list of matchup types so a user can select the one they want when they create the tournament
		 * The array to return should have key: the class name (which you can get using get_class()) and the value: the result for that objects getName()
		 */
	}
	
	/**
	 * Returns the name of the matchup type, for display purposes
	 * @return string
	 */
	abstract public function getName();
	
	/**
	 * Constructor for the Model_MatchupType_Abstract object
	 */
	function Model_MatchupType_Abstract()
	{
		$this->_participantList = new Model_ParticipantList();
	}
	
	/**
	 * Finds the number of matches required to neatly fit in to a tree
	 * @return integer
	 */
	protected function _numMatchesRequired()
	{
		return $this->_getNextPowerOfTwo($this->_participantList->numParticipants() / 2);
	}
	
	/**
	 * Removes participants from the participant list
	 * @param $participant A Model_Participant or Model_ParticipantList to remove
	 * @return $this
	 */
	public function removeParticipant($participant)
	{
		$this->_participantList->removeParticipant($participant);
		return $this;
	}
}