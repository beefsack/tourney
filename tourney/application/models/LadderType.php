<?php

class LadderType
{
	// $_ladderArray is the actual ladder data
	private $_ladderArray = array();
	
	// $_sortPriority is an array showing the fields to be sorted.  By default it is set to sort by points first, then wins second
	private $_sortPriority = array('points', 'wins');
	
	/**
	 * Clears the current ladder
	 */
	public function clearLadder()
	{
		unset($this->_ladderArray);
	}
	
	/**
	 * Gets the titles of all the different columns.
	 * @return array
	 */
	public function getColumnTitles()
	{
		// @todo Write getColumnTitles
	}
	
	/**
	 * Gets the ladder array
	 * @return array
	 */
	public function getLadder()
	{
		return $this->_ladderArray;
	}
	
	/**
	 * Inserts a row to the ladder table in the correct sorted position. 
	 * @param $data Array containing the data.  The key for each piece of data will be used as a column heading.
	 * @return $this
	 */
	public function insertRow($data)
	{
		// @todo Write insertRow
		return $this;
	}
	
	/**
	 * Changes the sort priority.  After setting a new priority, the ladder is sorted again.
	 * @param $data Array containing the new order of fields.
	 * @return $this
	 */
	public function setSortPriority($data)
	{
		// @todo Write setSortPriority
		return $this;
	}
	
	/**
	 * Sorts the ladder depending on the priority.
	 */
	private function _sort()
	{
		// @todo Write _sort
	}
}