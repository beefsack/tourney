<?php

abstract class Model_MatchupType_Abstract
{
	// List of participants
	protected $_participantList;
	
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