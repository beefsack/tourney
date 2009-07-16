<?php

class Model_MatchupType_Skill extends Model_MatchupType_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see models/MatchupType/Model_MatchupType_Abstract#getMatchups()
	 */
	public function getMatchups()
	{
		// @todo write getMatchups
		/*
		 * The Skill matchup type tries to create matchup brackets matching people with skill, and separating the good players from the bad players as much as possible.
		 * An 8 player matchup would look like this (after players have been given seed numbers):
		 * {1, 2}
		 * {3, 4}
		 * {5, 6}
		 * {7, 8}
		 * This means that the only way the worst team can play the best team is if they make it all the way to the final, trying to give everyone an even chance to get as much play, even if the final is one sided.
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
		return "Skill";
	}
}