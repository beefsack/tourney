<?php

class Model_MatchupType_Order extends Model_MatchupType_Abstract
{
	protected function _build(array $array, $matchesLeft)
	{
		if ($matchesLeft > 1) {
			$splitPoint = ceil(count($array) / 2);
			return array_merge(
				$this->_build(
					array_slice($array, 0, $splitPoint),
					$matchesLeft / 2
				),
				$this->_build(
					array_slice($array, $splitPoint),
					$matchesLeft / 2
				)
			);
		} else {
			return array($array);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/MatchupType/Model_MatchupType_Abstract#getMatchups()
	 */
	public function getMatchups()
	{
		/*
		 * Order matchups are the most simple of the lot
		 * It just makes brackets in the order that was passed to it
		 * A matchup style array is a multidimensional array as follows:
		 * The first dimension is a bracket containing participants
		 * The second dimension is a list of teams inside that bracket
		 * So: $matchuparray[1][0] gets the bracket number 1, then participant number 0 inside that
		 * 
		 * To make things easier for the tourney tree maker, the number of brackets (the first dimension of the matchup array) should be a power of 2.
		 * To accomodate for this, it is ok for brackets to have 1 participant instead of two, and the tree builder will just automatically advance players up the tree to face the result of the neighbour bracket.
		 * The way to find the next highest power of 2 above a number (the total participants), use the following expression
		 * pow(2, ceil(log($participantTotal, 2)))
		 */
		$participantArray = array();
		foreach ($this->_participantList as $p) {
			$participantArray[] = $p; 
		}
		return $this->_build($participantArray, $this->_numMatchesRequired());
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/MatchupType/Model_MatchupType_Abstract#getTypeName()
	 */
	public function getTypeName()
	{
		return "Order";
	}
}