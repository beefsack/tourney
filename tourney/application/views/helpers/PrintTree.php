<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom

class Zend_View_Helper_PrintTree extends Zend_View_Helper_Abstract
{
	public function printTree(Model_TreeType $tree)
	{
		echo $this->_stepTree($tree);
	}
	
	protected function _stepTree(Model_TreeType $tree)
	{
		if ($tree->data() instanceof Model_Match) {
			$str .= "<div class=\"treematch\">\n";
			$str .= $tree->data();
			$str .= "</div>\n";
		}
		if ($tree->left() !== NULL) {
			$str .= "<div class=\"treeleft\">\n";
			$str .= $this->_stepTree($tree->left());
			$str .= "</div>\n";
		}
		if ($tree->right() !== NULL) {
			$str .= "<div class=\"treeright\">\n";
			$str .= $this->_stepTree($tree->right());
			$str .= "</div>\n";
		}
		return $str;
	}
}
