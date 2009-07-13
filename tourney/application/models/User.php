<?php

class Model_User implements Model_Participantable
{
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	// User password = db password field
	protected $_password;
	// User username = db username field
	protected $_username;
	
	/**
	 * Returns the username
	 * @return string
	 */
	public function __toString()
	{
		return $this->_username();
	}
	
	/**
	 * Singleton method to get the user table class
	 * @return Model_DbTable_User
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_User();
		}
		return $this->_table;
	}
	
	public function getId()
	{
		return $this->_username;
	}
	
	/**
	 * Returns the hashed password
	 * @return string
	 */
	public function getPassword()
	{
		return $this->_password;
	}
	
	/**
	 * Returns the username
	 * @return string
	 */
	public function getUsername()
	{
		return $this->_username;
	}
	
	/**
	 * Load a user from the database into this object
	 * @param $username Username to load
	 * @return $this
	 */
	public function load($username)
	{
		// @todo write load
		return $this;
	}
	
	/**
	 * Constructor
	 * @param $username Optional username to load
	 */
	function Model_User($username = '')
	{
		if ($username > '') {
			$this->load($username);
		}
	}
	
	/**
	 * Saves this user object to the database
	 * @return $this
	 */
	public function save()
	{
		// @todo write save
		return $this;
	}
	
	/**
	 * Hashes the value and sets it as the password
	 * @param $value Password to hash and set
	 * @return $this
	 */
	public function setPassword($value)
	{
		// @todo write setPassword
		return $this;
	}
	
	/**
	 * Sets the username
	 * @param $value Username to set
	 * @return $this
	 */
	public function setUsername($value)
	{
		$this->_username = $value;
		return $this;
	}
}
