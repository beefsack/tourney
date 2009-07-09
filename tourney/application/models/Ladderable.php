<?php

interface Ladderable
{
	/**
	 * Returns a Ladder containing the relevant standings for the tournament
	 * @return LadderType
	 */
	public function getLadder();
}