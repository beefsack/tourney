<?php

interface Model_Treeable
{
	/**
	 * Returns a structured tree of the tournament
	 * @return Tourney_TreeType
	 */
	public function getTree();
}