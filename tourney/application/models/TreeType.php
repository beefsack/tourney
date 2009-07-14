<?php

class Model_TreeType
{
	// $_data is the data to be held at this node
	private $_data;
	
	// $_leftNode is the left node.  isset($_leftNode) will be false if no node is set
	private $_leftNode;
	
	// $_rightNode is the right node.  isset($_rightNode) will be false if no node is set
	private $_rightNode;
	
	/**
	 * Counts leaves below this node
	 * @return Integer
	 */
	public function countLeaves()
	{
		// @todo Write countLeaves
		/*
		 * countLeaves should be implemented recursively
		 * A TreeType object is a leaf if isset(left()) and isset(right()) are false
		 * Pseudocode could be something like:
		 * if left is set, increment total by left->countLeaves
		 * if right is set, increment total by right->countLeaves
		 * if left and right are both not set, set total to 1
		 * return total
		 */
		return $count;
	}
	
	/**
	 * Counts nodes below and including this node
	 * @return Integer
	 */
	public function countNodes()
	{
		// @todo Write countNodes
		/*
		 * countNodes should be implemented recursively
		 * Starting at the root, basically it should be return 1 + left->countNodes + right->countNodes
		 * It would have to check first that left and right are set, cos calling countNodes on a null object would make PHP a bit emotional
		 */
		return $count;
	}
	
	/**
	 * Get the data contained in this node
	 * @return Mixed
	 */
	public function data()
	{
		return $this->_data;
	}
	
	/**
	 * Get the left node
	 * @return TreeType
	 */
	public function &left()
	{
		return $this->_leftNode;
	}

	/**
	 * Get the right node
	 * @return TreeType
	 */
	public function &right()
	{
		return $this->_rightNode;
	}
	
	/**
	 * Set the left node
	 * @param $data The data to be held in the left node
	 * @return $this
	 */
	public function setLeft(&$data)
	{
		// @todo Write setLeft
		return $this;
	}
	
	/**
	 * Set the right node
	 * @param $data The data to be held in the right node
	 * @return $this
	 */
	public function setRight(&$data)
	{
		// @todo Write setRight
		return $this;
	}
	
	/**
	 * Returns the depth of the tree.  If the current node has no children, depth is 1.
	 * @return integer
	 */
	public function treeDepth()
	{
		// @todo Write treeDepth
		/*
		 * treeDepth should be implemented recursively
		 */
		return $depth;
	}	
}