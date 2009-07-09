<?php

interface Tourney_Treeable
{
	/**
	 * Returns a structured tree of the tournament
	 * @return Tourney_TreeType
	 */
	public function getTree();
}