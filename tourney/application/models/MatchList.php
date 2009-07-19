<?php

class Model_MatchList implements Iterator
{
	// Actual array to store matches in
	protected $_list = array();
	
	/**
	 * Add a Model_Match or Model_MatchList object to the list.
	 * @param $match Model_Match or Model_MatchList to add to the list.
	 * @return $this
	 */
	public function addMatch($match)
	{
		// @todo write addMatch
		/*
		 * This will have to behave differently depending on what type of object is passed as $match
		 * If it is a Model_Match, all it has to do is add it to the array
		 * If it is a Model_MatchList, it will have to loop through Model_MatchList (using foreach) and add each item individually
		 * You can check class type like this: if ($match instanceof Model_Match) {
		 * If it is not a Match or MatchList, it should throw a new exception
		 */
		return $this;
	}
	
	/**
	 * Get the current item when iterating.  Required by Iterator interface.
	 * @return Model_Match
	 */
	public function current()
	{
		return current($this->_list);
	}
	
	/**
	 * Clear the match list
	 * @return $this
	 */
	public function clearMatchList()
	{
		unset($this->_list);
		return $this;
	}
	
	/**
	 * Get the current key when iterating.  Required by Iterator interface.
	 * @return Mixed
	 */
	public function key()
	{
		return key($this->_list);
	}
	
	/**
	 * Loads all matches for a specific tournament id
	 * @param $index Tourney id
	 * @return $this
	 */
	public function load($index)
	{
		// @todo write load
		/*
		 * A slightly more complicated load than the others
		 * The param passed to this function is the index of a tourney
		 * What this function first has to do is run a query to find all matches with a tourneyid the same as index
		 * Then, for each match, a new Model_Match object is created passing the corresponding match id
		 * After each Model_Match is created, it should be passed to the addMatch of this class to add it to the array
		 */
		return $this;
	}
	
	/**
	 * Get the next item when iterating.  Required by Iterator interface.
	 * @return Model_Match
	 */
	public function next()
	{
		return next($this->_list);
	}
	
	/**
	 * Returns the number of matches in this list
	 * @return integer
	 */
	public function numMatches()
	{
		return count($this->_list);
	}
	
	/**
	 * Remove the specified matches from the list, if they exists
	 * @param $match Model_Match or Model_MatchList to remove from the list.
	 * @return $this
	 */
	public function removeMatch($match)
	{
		// @todo write removeMatch
		/*
		 * Can get a Model_Match or Model_MatchList to remove from the current Model_MatchList
		 * If it is a match, the function should loop through this array and remove the match if found
		 * If it is a match list, this function should loop through each item of the match list and call this function again on each individual match in $match
		 * If $match is neither a Model_Match or a Model_MatchList, a new exception should be thrown
		 * instanceof should be used to check the class type of $match, eg: if ($match instanceof Model_Match)
		 */
		return $this;
	}
	
	/**
	 * Get the previous item when iterating.  Required by Iterator interface.
	 * @return Model_Match
	 */
	public function rewind()
	{
		return reset($this->_list);
	}
	
	/**
	 * Loops through all of the matches and calls save on each
	 * @return $this
	 */
	public function save()
	{
		// @todo write save
		/*
		 * Calls save on each of the Model_Matches inside $_list
		 */
		return $this;
	}
	
	/**
	 * Check if current item is valid.  Required by Iterator interface.
	 * @return boolean
	 */
	public function valid()
	{
		return $this->current() !== false;
	}
}