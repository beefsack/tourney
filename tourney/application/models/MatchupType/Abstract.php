<?php

abstract class Model_MatchupType_Abstract
{
	// List of participants
	protected $_participantList = array();
	
	/**
	 * Adds a participant to the participant list
	 * @param $participant A single participant or an array of participants
	 * @return $this
	 */
	public function addParticipant($participant)
	{
		// @todo write addParticipant
		return $this;
	}
	
	/**
	 * Clear the participant list
	 * @return $this
	 */
	public function clearParticipants()
	{
		unset($this->_participantList);
		return $this;
	}
	
	/**
	 * Returns a multidimensional array of pairings of players
	 * @return array
	 */
	abstract public function getMatchups();
	
	/**
	 * Returns the name of the matchup type, for display purposes
	 * @return string
	 */
	abstract public function getName();
	
	/**
	 * Removes a participant from the participant list
	 * @param $participant A single participant or an array of participants
	 * @return $this
	 */
	public function removeParticipant($participant)
	{
		// @todo write removeParticipant
		return $this;
	}
}