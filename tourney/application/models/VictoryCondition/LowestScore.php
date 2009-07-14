<?php

class Model_VictoryCondition_LowestScore extends Model_VictoryCondition_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see models/VictoryCondition/Model_VictoryCondition_Abstract#getStandings($participantlist)
	 */
	public function &getStandings(Model_ParticipantList &$participantlist)
	{
		// @todo write getStandings
		/*
		 * Sets the result of each participant from lowest score to highest score.  An example use for this would be a golf game.
		 */
		return $participantlist;
	}
}