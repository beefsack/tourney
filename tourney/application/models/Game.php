<?php

class Model_Game
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
	function Game($index = 0)
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
	 * Load a game from the database
	 * @param $index Index of game to load
	 */
	public function load($index)
	{
		// @todo write load
	}
	
	public function save()
	{
		if (!isset($this->_name)) {
			throw new Exception('$_name not set in Model_Game object');
		}
		if (!isset($this->_description)) {
			throw new Exception('$_description not set in Model_Game object');
		}
		if (!isset($this->_scoringtype)) {
			throw new Exception('$_scoringtype not set in Model_Game object');
		}
		// @todo write save
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
		$this->_name = value;
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