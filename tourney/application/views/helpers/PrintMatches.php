<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom

class Zend_View_Helper_PrintMatches extends Zend_View_Helper_Abstract
{
	public function printMatches(Model_MatchList $matches)
	{
		echo "<div class=\"tree\">\n";
		echo "SOMESHIT\n";
		echo "</div>\n";
	}
}