<?php

interface Treeable
{
	/**
	 * Returns a structured tree of the tournament
	 * @return TreeType
	 */
	public function getTree();
}