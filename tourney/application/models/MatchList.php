<?php

class Match_List implements Iterator
{
	protected $_list = array();
	
	/**
	 * Add a Model_Match or Model_MatchList object to the list.
	 * @param $match Model_Match or Model_MatchList to add to the list.
	 * @return $this
	 */
	public function addMatch($match)
	{
		// @todo write addMatch
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
	 * Get the next item when iterating.  Required by Iterator interface.
	 * @return Model_Match
	 */
	public function next()
	{
		return next($this->_list);
	}
	
	/**
	 * Remove the specified matches from the list, if they exists
	 * @param $match Model_Match or Model_MatchList to remove from the list.
	 * @return $this
	 */
	public function removeMatch($match)
	{
		// @todo write removeMatch
		return $this;
	}
	
	/**
	 * Get the previous item when iterating.  Required by Iterator interface.
	 * @return Model_Match
	 */
	public function rewind()
	{
		return rewind($this->_list);
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