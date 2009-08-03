<?php

class Model_Game implements Model_Interface_Unique
{
	// The description of the game = db description column
	protected $_description;
	// The id of the game = db id column.  0 if not saved in the database yet
	protected $_id = 0;
	// The name of the game = db name column
	protected $_name;
	// The scoringtype of the game = db scoringtype column
	protected $_scoringtype;
	// Instance of the DbTable to directly access the database.  Accessed via $this->_getTable()
	static protected $_table;
	
	/**
	 * Singleton method to get the game table class
	 * @return Model_DbTable_Game
	 */
	protected function _getTable()
	{
		if (!isset($this->_table)) {
			$this->_table = new Model_DbTable_Game();
		}
		return $this->_table;
	}
	
	/**
	 * Constructor
	 * @param $index Index of game to load
	 */
	function Model_Game($index = 0)
	{
		if ($index > 0) {
			$this->load($index);
		}
	}

	/**
	 * Gets the description of the game
	 * @return string
	 */
	public function getDescription()
	{
		return $this->_description;
	}
	
	/**
	 * Gets the id of the game
	 * @return integer
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Gets the name of the game
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Gets the scoringtype of the game
	 * @return string
	 */
	public function getScoringtype()
	{
		return $this->_scoringtype;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Interface/Model_Interface_Unique#getUniqueId()
	 */
	public function getUniqueId()
	{
		return $this->_id;
	}
	
	/**
	 * Load a game from the database
	 * @param $index Index of game to load
	 */
	public function load($id)
	{
		// Reference on select objects and executing them: http://zendframework.com/manual/en/zend.db.select.html
		$select = $this->_getTable()->select()->where('id = ?', $id);
		$stmt = $select->query();
		$result = $stmt->fetch();
		if (!$result) {
			throw new Exception("id '$id' not found");
		}
		$this->_id = $result['id'];
		$this->_name = $result['name'];
		$this->_description = $result['description'];
		$this->_scoringtype = $result['scoringtype'];	
		return $this;			
	}
	
	public function save()
	{
		if (!isset($this->_name)) {
			throw new Exception('$_name not set in Model_Game object');
		}
		if (!isset($this->_scoringtype)) {
			throw new Exception('$_scoringtype not set in Model_Game object');
		}
		/*
		 * an easy save, there are already checks written to make sure the three members are set
		 * Next thing to do would be to check if $_id is set or not
		 * if $_id is set that means that this game is already in the database, so do an update
		 * If $_id is not set, that means it is not yet in the database and requires to be inserted
		 */
		$table = $this->_getTable();
		$data['name'] = $this->_name;
		$data['description'] = $this->_description;
		$data['scoringtype'] = $this->_scoringtype;
		if ($this->_id > 0) {
			$table->update($data, $table->getAdapter()->quoteInto('id = ?', $this->_id));
		} else {
			$table->insert($data);
			$this->_id = $table->getAdapter()->lastInsertId();
		}
		return $this;
	}
	
	/**
	 * Sets the description of the game
	 * @param $value Description
	 * @return $this
	 */
	public function setDescription($value)
	{
		$this->_description = $value;
		return $this;
	}
	
	/**
	 * Sets the name of the game
	 * @param $value Name
	 * @return $this
	 */
	public function setName($value)
	{
		$this->_name = $value;
		return $this;
	}
	
	/**
	 * Sets the scoring type of the game
	 * @param Model_VictoryCondition_Abstract $obj Scoring type
	 * @return $this
	 */
	public function setScoringtype(Model_VictoryCondition_Abstract $obj)
	{
		$this->_scoringtype = get_class($obj);
		return $this;
	}
}