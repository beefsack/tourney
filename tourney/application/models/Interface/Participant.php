<?php

interface Model_Interface_Participant
{
	/**
	 * Uses the __toString method for view output.  Intended to be a link to a page about the participant
	 * @return string
	 */
	public function __toString();
	
	/**
	 * Returns the database id of the object
	 * @return $this
	 */
	public function getId();
	
	/**
	 * Loads the object from the database
	 * @param $index Index to load
	 * @return $this
	 */
	public function load($index);
}