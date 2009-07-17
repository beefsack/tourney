<?php

interface Model_Treeable
{
	public function getMatchupType();
	
	/**
	 * Returns a structured tree of the tournament
	 * @return Tourney_TreeType
	 */
	public function getTree();
	
	public function setMatchupType(Model_MatchupType_Abstract $matchup);
}