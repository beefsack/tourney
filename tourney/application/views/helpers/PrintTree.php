<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom

class Zend_View_Helper_PrintTree extends Zend_View_Helper_Abstract
{
	public function printTree(Model_TreeType $tree)
	{
		echo "<div class=\"tree\">\n";
		echo "SOMESHIT\n";
		echo "</div>\n";
	}
}
