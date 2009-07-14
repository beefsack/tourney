<?php

class Model_VictoryCondition_HighestScore extends Model_VictoryCondition_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see models/VictoryCondition/Model_VictoryCondition_Abstract#getStandings($participantlist)
	 */
	public function &getStandings(Model_ParticipantList &$participantlist)
	{
		// @todo write getStandings
		/*
		 * The highest score victory condition just sets the result value of each participant in order from highest score to lowest score
		 */
		return $participantlist;
	}
}