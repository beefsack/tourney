<?php

abstract class Model_VictoryCondition_Abstract
{
	/**
	 * Checks all scores and returns a sorted list from first to last place
	 * @param Model_ParticipantList $participantlist List of participants along with scores.  Blank score is assumed to be 0
	 * @return Model_ParticipantList
	 */
	abstract public function &getStandings(Model_ParticipantList &$participantlist);
}