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
		return new Model_ParticipantList();
	}
}