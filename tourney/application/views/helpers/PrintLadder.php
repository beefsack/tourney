<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom

class Zend_View_Helper_PrintLadder extends Zend_View_Helper_Abstract
{
	public function printLadder(Model_LadderType $ladder)
	{
		echo "<div class=\"ladder\">\n";
		echo "SOMESHIT\n";
		echo "</div>\n";
	}
}