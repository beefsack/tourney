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
		$returnStr = "";
		if (is_array($this->_data)) {
			foreach ($this->_data as $key => $data) {
				$returnStr .= "$key:$data;";
			}
		}
		return $returnStr;
	}
	
	/**
	 * Takes data and loads it into the local array.  Can be string in key:value; notation, an array or another Model_TourneyData
	 * @param $data
	 */
	public function add($data)
	{
		if (is_string($data)) {
			// Handle for string
			$options = split(';', $data);
			foreach ($options as $option) {
				$tokens = split(':', $option, 2);
				if (count($tokens) == 2) {
					$this->_data[$tokens[0]] = $tokens[1];
				}
			}
		} elseif (is_array($data)) {
			// Handle for array
			foreach ($data as $key => $item) {
				$this->_data[$key] = $item;
			}
		} elseif ($data instanceof Model_TourneyData) {
			// Handle for another instance of Model_TourneyData
			foreach ($data->getArray() as $key => $item) {
				$this->_data[$key] = $item;
			}
		}
	}
	
	/**
	 * Clears the array
	 */
	public function clear()
	{
		unset($this->_data);
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
	 * Returns the raw array of data
	 * @return array
	 */
	public function getArray()
	{
		return $this->_data;
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
	function Model_TourneyData($data = NULL)
	{
		$this->add($data);
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
		return reset($this->_data);
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