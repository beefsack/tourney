<?php

class Model_User implements Model_Interface_Participant, Model_Interface_Unique
{
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	// User password = db password field
	protected $_password;
	// User name = db name field
	protected $_name;
	
	/**
	 * Returns the name
	 * @return string
	 */
	public function __toString()
	{
		$url = Zend_View_Helper_Url::url(
			array('controller' => 'user',
				'action' => 'view',
				'id' => $this->_name,
			), NULL, true);
		return "<a href=\"" . $url . "\">" . $this->_name . "</a>";
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
	
	/**
	 * (non-PHPdoc)
	 * @see models/Interface/Model_Interface_Participant#getId()
	 */
	public function getId()
	{
		return $this->_name;
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
	 * Returns the name
	 * @return string
	 */
	public function getname()
	{
		return $this->_name;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Interface/Model_Interface_Unique#getUniqueId()
	 */
	public function getUniqueId()
	{
		return $this->_name;
	}
	
	/**
	 * Load a user from the database into this object
	 * @param $name name to load
	 * @return $this
	 */
	public function load($name)
	{
		// Reference on select objects and executing them: http://zendframework.com/manual/en/zend.db.select.html
		$select = $this->_getTable()->select()->where('name = ?', $name);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if (!$result) {
			throw new Exception("name '$name' not found");
		}
		$this->_name = $result['name'];
		$this->_password = $result['password'];
		return $this;
	}
	
	/**
	 * Constructor
	 * @param $name Optional name to load
	 */
	function Model_User($name = '')
	{
		if ($name > '') {
			$this->load($name);
		}
	}
	
	/**
	 * Saves this user object to the database
	 * @return $this
	 */
	public function save()
	{
		// Make sure username is set
		if (!$this->_name) {
			throw new Exception('Unable to save user as no username specified');
		}
		// Prepare data for storing
		$data = array(
			'name' => $this->_name,
			'password' => $this->_password,
		);
		// Check if there is already a row for this username
		$select = $this->_getTable()->select()->where('name = ?', $this->_name);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if ($result) {
			// There is a row, so just update
			$this->_getTable()->update($data, $this->_getTable()->getAdapter()->quoteInto('name = ?', $this->_name));
		} else {
			// There is no row, so insert
			$this->_getTable()->insert($data);			
		}
		return $this;
	}
	
	/**
	 * Hashes the value and sets it as the password
	 * @param $value Password to hash and set
	 * @return $this
	 */
	public function setPassword($value)
	{
		// Zend_Auth will be set up to use SHA1
		$this->_password = sha1($value);
		return $this;
	}
	
	/**
	 * Sets the name
	 * @param $value name to set
	 * @return $this
	 */
	public function setName($value)
	{
		$this->_name = $value;
		return $this;
	}
}
