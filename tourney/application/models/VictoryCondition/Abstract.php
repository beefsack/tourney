<?php

abstract class Model_VictoryCondition_Abstract
{
	/**
	 * Compares the two values depending on the 
	 * @param array $participants
	 * @return 0 for draw, 1 for val1, 2 for val2
	 */
	abstract protected function _compare($val1, $val2);
	
	/**
	 * Checks all scores and returns a sorted list from first to last place
	 * @param Model_ParticipantList $participantlist List of participants along with scores.  Blank score is assumed to be 0
	 * @return Model_ParticipantList
	 */
	public function getStandings(Model_ParticipantList $participantlist)
	{
		// @todo write getStandings
	}
}
