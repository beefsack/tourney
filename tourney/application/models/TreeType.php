<?php

class Model_TreeType
{
	// $_data is the data to be held at this node
	private $_data;
	
	// $_leftNode is the left node.  isset($_leftNode) will be false if no node is set
	private $_left;
	
	// $_rightNode is the right node.  isset($_rightNode) will be false if no node is set
	private $_right;
	
	/**
	 * Counts leaves below this node
	 * @return Integer
	 */
	public function countLeaves()
	{
		/*
		 * countLeaves should be implemented recursively
		 * A TreeType object is a leaf if isset(left()) and isset(right()) are false
		 * Pseudocode could be something like:
		 * if left is set, increment total by left->countLeaves
		 * if right is set, increment total by right->countLeaves
		 * if left and right are both not set, set total to 1
		 * return total
		 */
		$leaves = 0;
		if ($this->_left === NULL && $this->_right === NULL) {
			$leaves = 1;
		} else {
			if ($this->_left !== NULL && $this->_left instanceof Model_TreeType) {
				$leaves += $this->_left->countLeaves();
			}
			if ($this->_right !== NULL && $this->_right instanceof Model_TreeType) {
				$leaves += $this->_right->countLeaves();
			}
		}
		return $leaves;
	}
	
	/**
	 * Counts nodes below and including this node
	 * @return Integer
	 */
	public function countNodes()
	{
		/*
		 * countNodes should be implemented recursively
		 * Starting at the root, basically it should be return 1 + left->countNodes + right->countNodes
		 * It would have to check first that left and right are set, cos calling countNodes on a null object would make PHP a bit emotional
		 */
		$count = 1;
		if ($this->_left !== NULL) {
			$count += $this->_left->countNodes();
		}
		if ($this->_right !== NULL) {
			$count += $this->_right->countNodes();
		}
		return $count;
	}
	
	/**
	 * Get the data contained in this node
	 * @return Mixed
	 */
	public function &data()
	{
		return $this->_data;
	}
	
	/**
	 * Get the left node
	 * @return TreeType
	 */
	public function &left()
	{
		return $this->_left;
	}
	
	/**
	 * Constructor
	 * @param $data Optional data to set
	 */
	public function Model_TreeType(&$data = NULL)
	{
		if ($data !== NULL) {
			$this->setData($data);
		}
	}

	/**
	 * Get the right node
	 * @return TreeType
	 */
	public function &right()
	{
		return $this->_right;
	}
	
	/**
	 * Sets the data
	 * @param $data The data to be held in this node
	 * @return $this
	 */
	public function setData(&$data)
	{
		$this->_data =& $data;
		return $this;
	}
	
	/**
	 * Sets the left node
	 * @param TreeType $node The node to set
	 * @return $this
	 */
	public function setLeft(Model_TreeType $node)
	{
		$this->_left = $node;
		return $this;
	}
	
	/**
	 * Sets the right node
	 * @param TreeType $node The node to set
	 * @return $this
	 */
	public function setRight(Model_TreeType $node)
	{
		$this->_right = $node;
		return $this;
	}
	
	/**
	 * Returns the depth of the tree.  If the current node has no children, depth is 1.
	 * @return integer
	 */
	public function treeDepth()
	{
		/*
		 * This finds how deep the tree goes.  That would be massively useful for finding alignments for outputting a tree as an image, or maybe with css.
		 * treeDepth should be implemented recursively
		 * could be implemented like this:
		 * return the maximum of (left node->treeDepth + 1, right node->treeDepth + 1)
		 * That would recurse through every single node, but would only return the deepest one
		 */
		//echo "calling depth";
		$depth = 0;
		if ($this->_left !== NULL && $this->_left instanceof Model_TreeType) {
			$depth = $this->_left->treeDepth();
		}
		if ($this->_right !== NULL && $this->_right instanceof Model_TreeType) {
			$depth = max($depth, $this->_right->treeDepth());
		}
		return ++$depth;
	}	
}