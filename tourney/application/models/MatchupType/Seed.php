<?php

class Model_MatchupType_Seed extends Model_MatchupType_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see models/MatchupType/Model_MatchupType_Abstract#getMatchups()
	 */
	public function getMatchups()
	{
		// @todo write getMatchups
		/*
		 * The seed matchup type is designed to allow the top two players to be able to meet in a final
		 * Each player is given a seed number depending on their ranking.  1 is for best player
		 * Not sure how to rank people, there could be many ways to do it.  Maybe a win/loss ratio in the last x games would be a good idea
		 * Matchups for an 8 player seeded tournament would look like this
		 * {1, 8}
		 * {4, 5}
		 * {3, 6}
		 * {2, 8}
		 * For a 16 player tournament it would look like this
		 * {1, 16}
		 * {8, 9}
		 * {5, 12}
		 * {4, 13}
		 * {3, 14}
		 * {6, 11}
		 * {7, 10}
		 * {2, 15}
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
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/MatchupType/Model_MatchupType_Abstract#getName()
	 */
	public function getName()
	{
		return "Seed";
	}
}