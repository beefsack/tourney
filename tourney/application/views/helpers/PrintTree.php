<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom

class Zend_View_Helper_PrintTree extends Zend_View_Helper_Abstract
{
	public function printTree(Model_TreeType $tree)
	{
		$str .= "<div class=\"tree\">\n";
		$str .= $this->_stepTree($tree, $tree->treeDepth());
		$str .= "</div>\n";
		echo $str;
	}
	
	protected function _stepTree(Model_TreeType $tree, $treeDepth, $blankTree = false, $root = true, $baseHeight = 100, $baseWidth = 100)
	{
		$powdepth = pow(2, $treeDepth - 1);
		$str .= "<div class=\"treecontainer\"";
		if ($root) $str .= " style=\"width:" . ($treeDepth * $baseWidth * 2 - $baseWidth + $treeDepth) . "px;height:" . ($powdepth * $baseHeight + $powdepth) . "px;\"";
		$str .= ">
<div class=\"treecol\">
<div class=\"treespacer\" style=\"height:" . (($powdepth * $baseHeight - $baseHeight) / 2) . "px;\"></div>
<div class=\"treematchholder\">
<div class=\"treeverthax\">
<div class=\"treematch\">
<div class=\"treematchinfo\">1/1/1970</div>
<div class=\"treeparticipant treewinner\">
<div class=\"treeparticipantname\">mick</div>
<div class=\"treeparticipantscore\">100000</div>
</div>
<div class=\"treeparticipant treeloser\">
<div class=\"treeparticipantname\">steve</div>
<div class=\"treeparticipantscore\">1</div>
</div>
</div>
</div>
</div>
<div class=\"treespacer\" style=\"height:" . (($powdepth * $baseHeight - $baseHeight) / 2) . "px;\"></div>
</div>\n";
		if (($tree->left() !== NULL || $tree->right() !== NULL) && $treeDepth > 1) {
			$str .= "<div class=\"treecol\">
<div class=\"treehline\" style=\"height:" . ($powdepth * $baseHeight / 2) . "px;\"></div>
<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 2) . "px;\"></div>
</div>
<div class=\"treecol\">
<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 4) . "px;\"></div>\n";
			if ($tree->left() !== NULL) {
				$str .= "<div class=\"treeupbranchline\" style=\"height:" . ($powdepth * $baseHeight / 4) . "px;\"></div>\n";
			} else {
				$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 4) . "px;\"></div>\n";
			}
			if ($tree->right() !== NULL) {
				$str .= "<div class=\"treedownbranchline\" style=\"height:" . ($powdepth * $baseHeight / 4) . "px;\"></div>\n";
			} else {
				$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 4) . "px;\"></div>\n";
			}
			$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 4) . "px;\"></div>
</div>
<div class=\"treecol\">\n";
			if ($tree->left() !== NULL) {
				$str .= $this->_stepTree($tree->left(), $treeDepth - 1, $blankTree, false, $baseHeight, $baseWidth);
			} else {
				$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 2) . "px;\"></div>\n";
			}
			if ($tree->right() !== NULL) {
				$str .= $this->_stepTree($tree->right(), $treeDepth - 1, $blankTree, false, $baseHeight, $baseWidth);
			} else {
				$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 2) . "px;\"></div>\n";
			}
			$str .= "</div>\n";
		}
		$str .= "</div>\n";
		return $str;
	}
}
