<?php

class Model_MatchList implements Iterator
{
	// Actual array to store matches in
	protected $_list = array();
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
	/**
	 * Singleton method to get the participant table class
	 * @return Model_DbTable_Participant
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Match();
		}
		return $this->_table;
	}
	
	
	function Model_MatchList($index = 0)
	{
		if ($index > 0) {
			$this->load($index);
		}
	}
	/**
	 * Add a Model_Match or Model_MatchList object to the list.
	 * @param $match Model_Match or Model_MatchList to add to the list.
	 * @return $this
	 */
	public function addMatch($match)
	{
		/*
		 * This will have to behave differently depending on what type of object is passed as $match
		 * If it is a Model_Match, all it has to do is add it to the array
		 * If it is a Model_MatchList, it will have to loop through Model_MatchList (using foreach) and add each item individually
		 * You can check class type like this: if ($match instanceof Model_Match) {
		 * If it is not a Match or MatchList, it should throw a new exception
		 */
		if ($match instanceof Model_Match) {
			$this->_list[] = $match;
		} elseif ($match instanceof Model_MatchList) {
			foreach ($match as $m) {
				$this->addMatch($m);
			}
		} else {
			throw new Exception("addMatch called without passing a Model_Match or Model_MatchList");
		}
		return $this;
	}
	
	public function count()
	{
		return count($this->_list);
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
		/*
		* A slightly more complicated load than the others
		* The param passed to this function is the index of a tourney
		* What this function first has to do is run a query to find all matches with a tourneyid the same as index
		* Then, for each match, a new Model_Match object is created passing the corresponding match id
		* After each Model_Match is created, it should be passed to the addMatch of this class to add it to the array
		*/
		$select = $this->_getTable()->select()->where('tourneyid = ?', $index);
		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if (!$result) {
			throw new Exception("tourneyid '$index' not found");
		}
		foreach($result as $row) {
			$this->addMatch(new Model_Match($row['id']));
		}
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
		/*
		* Can get a Model_Match or Model_MatchList to remove from the current Model_MatchList
		* If it is a match, the function should loop through this array and remove the match if found
		* If it is a match list, this function should loop through each item of the match list and call this function again on each individual match in $match
		* If $match is neither a Model_Match or a Model_MatchList, a new exception should be thrown
		* instanceof should be used to check the class type of $match, eg: if ($match instanceof Model_Match)
		*/
		if ($match instanceof Model_Match) {
			foreach ($this->_list as $key => $m) {
				if ($m === $match) {
					unset($this->_list[$key]);
				}
			}
		} elseif ($match instanceof Model_MatchList) {
			foreach ($match as $m) {
				$this->removeMatch($m);
			}
		} else {
			throw new Exception("removeMatch called with non Model_Match or Model_MatchList");
		}
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
		/*
		* Calls save on each of the Model_Matches inside $_list
		*/
		foreach ($this->_list as $m)
		{
			if ($m instanceof Model_Match) {
				$m->save();
			}
		}
		
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