<?php

class Model_VictoryCondition_HighestScore extends Model_VictoryCondition_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see models/VictoryCondition/Model_VictoryCondition_Abstract#getStandings($participantlist)
	 */
	protected function _compare($val1, $val2)
	{
		if ($val1 > $val2) {
			return 1;
		} elseif ($val1 < $val2) {
			return 2;
		}
		return 0;
	}
}