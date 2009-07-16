<?php

class Model_VictoryCondition_HighestScore extends Model_VictoryCondition_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see models/VictoryCondition/Model_VictoryCondition_Abstract#getStandings($participantlist)
	 */
	public function getStandings(Model_ParticipantList $participantlist)
	{
		// @todo write getStandings
		/*
		 * The highest score victory condition just sets the result value of each participant in order from highest score to lowest score
		 * Because objects are passed by reference by default in PHP, don't make changes to $participantlist.
		 * Make a new one to return sorted
		 */
		return $participantlist;
	}
}