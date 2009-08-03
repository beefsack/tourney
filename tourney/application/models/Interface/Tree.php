<?php

interface Model_Interface_Tree
{
	/**
	 * Returns a structured tree of the tournament
	 * @return Tourney_TreeType
	 */
	public function getTree();
}