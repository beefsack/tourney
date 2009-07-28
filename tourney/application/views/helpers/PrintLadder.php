<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom
//$this->printLadder();
class Zend_View_Helper_PrintLadder extends Zend_View_Helper_Abstract
{
	public function printLadder(Model_LadderType $ladder)
	{
		echo "<table class=\"ladder\"><tr>";
		
		foreach($ladder->getColumnTitles() as $col)
		{
			echo "<th>";
			echo $col;
			
			
		};
		echo "</tr><tr>";
		
		foreach($ladder->getLadder() as $array)
		{	
			echo "<tr>";			
			foreach($ladder->getColumnTitles() as $col)
			{	
				echo "<td>";
				echo $array[$col];
			}
			echo "</tr>";
		}
			
			
		

	}
}