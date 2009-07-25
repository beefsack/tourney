<?php

class Model_LadderType
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
		/*
		 * This is mainly for use by controllers and views to make column headers when making a fancy looking table
		 * This method will loop through each row of the ladder array collecting the keys to find all the column titles
		 * This method should use a foreach (to get each row of data) inside a foreach (to get each column for each row)
		 * This method should return an array of strings, and shouldn't have duplicates of titles
		 */
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
	public function insertRow(array $data)
	{
		// @todo Write insertRow
		/*
		 * Very simple method, takes the $data array and adds it to this objects $_ladderarray
		 * PHP can add a new element to a dynamic array by not specifying an index, ie. $_ladderarray[] = someshit
		 */
		return $this;
	}
	
	/**
	 * Changes the sort priority.  After setting a new priority, the ladder is sorted again.
	 * @param $data Array containing the new order of fields.
	 * @return $this
	 */
	public function setSortPriority(array $data)
	{
		/*
		 * A very simple function to set $_sortPriority
		 * $_sortPriority to be set to the array in $data.
		 */
		$this->_sortPriority = $data;
		$this->_sort();
		return $this;
	}
	
	/**
	 * Sorts the ladder depending on the priority.
	 */
	private function _sort()
	{
		// @todo Write _sort
		/*
		 * This will do the actual sorting of the array $_ladderArray
		 * The $_sortPriority will be an array of keys
		 * Default is 'points' then 'wins', so $_ladderArray[0]['points'] will be the first thing to sort.
		 * This may be usable: http://us3.php.net/manual/en/function.sort.php (uses quicksort)
		 */
	}
}