<?php

class Model_TourneyData implements Iterator, ArrayAccess
{
	// The actual data to be stored
	protected $_data = array();
	
	/**
	 * Converts the data to a string to save in the database in key:value; notation
	 * @return string
	 */
	public function __toString()
	{
		// @todo write __toString
	}
	
	/**
	 * Takes data and loads it into the local array.  Can be string in key:value; notation, an array or another Model_TourneyData
	 * @param $data
	 */
	protected function _initialise($data)
	{
		// @todo write initialise
	}
	
	/**
	 * Current element for Iterator interface
	 * @return Mixed
	 */
	public function current()
	{
		return current($this->_data);
	}

	/**
	 * Current key for Iterator interface
	 * @return Mixed
	 */
	public function key()
	{
		return key($this->_data);
	}

	/**
	 * Constructor that takes data to initialise.  Data can be a string or it can be another Model_TourneyData or it can be a plain old array
	 * @param $data Data to load
	 */
	function Model_TourneyData($data)
	{
		$this->_initialise($data);
	}
	
	/**
	 * Default constructor, does nothing
	 */
	function Model_TourneyData()
	{
	}

	/**
	 * Next element for Iterator interface
	 * @return Mixed
	 */
	public function next()
	{
		return next($this->_data);
	}

	/**
	 * Check if the offset exists for ArrayAccess interface
	 * @param $offset Offset to check
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->_data[$offset]);
	}

	/**
	 * Get the value at the offset for ArrayAccess interface
	 * @param $offset Offset to get
	 * @return Mixed
	 */
	public function offsetGet($offset)
	{
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}

	/**
	 * Sets a value at the offset for ArrayAccess interface
	 * @param $offset Offset to set
	 * @param $value Value to set
	 */
	public function offsetSet($offset, $value)
	{
		$this->_data[$offset] = $value;
	}

	/**
	 * Unsets a value at the offset for ArrayAccess interface
	 * @param $offset Offset to unset
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

	/**
	 * Rewind element for Iterator interface
	 * @return Mixed
	 */
	public function rewind()
	{
		return rewind($this->_data);
	}
	
	/**
	 * Valid element for Iterator interface
	 * @return Mixed
	 */
	public function valid()
	{
		return $this->current() !== false;
	}
}