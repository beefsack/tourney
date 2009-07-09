<?php

interface Tourney_Ladderable
{
	/**
	 * Returns a Ladder containing the relevant standings for the tournament
	 * @return Tourney_LadderType
	 */
	public function getLadder();
}