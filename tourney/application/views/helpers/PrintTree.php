<?php

// http://framework.zend.com/manual/en/zend.view.helpers.html#zend.view.helpers.custom

class Zend_View_Helper_PrintTree extends Zend_View_Helper_Abstract
{
	
	
	public function printTree(Model_TreeType $tree, $treeonly = false)
	{
		if (!$treeonly) {
			$this->view->headScript()->appendFile(PUBLIC_PATH . '/js/match.js');
			$this->view->headScript()->appendFile(PUBLIC_PATH . '/js/tree.js');
			$theForm = new Form_ScoreInput;
			
			$theForm->setPlayers(1);
			
			$str.= $this->view->customDijit(
				'scoredialog',
				$theForm,
				array(
		        	'dojoType'	=> 'dijit.Dialog',
		        	'title'		=> 'Enter Scores',
		        	'region'	=> 'center',
					'style'		=> 'font-family: sans-serif; font-size: 10pt;'
	   			)
	   		);
			$str .= "<div id=\"treecontentpane\" dojoType=\"dijit.layout.ContentPane\" class=\"tree\">\n";
		}
		$str .= $this->_stepTree($tree);
		if (!$treeonly) {
			$str .= "</div>\n";
			$str .= "<script type=\"text/javascript\">
//<![CDATA[
	setPanElement(document.getElementById('treecontentpane'));
//]]>
</script>\n";
		}
		return $str;
	}
	
	protected function _stepTree(Model_TreeType $tree, $treeDepth = 0, $root = true, $baseHeight = 100, $baseWidth = 100)
	{
		if ($root) $treeDepth = $tree->treeDepth();
		$powdepth = pow(2, $treeDepth - 1);
		$data = $tree->data();
		$str .= "<div class=\"treecontainer\"";
		if ($root) $str .= " style=\"width:" . ($treeDepth * $baseWidth * 2 - $baseWidth + $treeDepth) . "px;height:" . ($powdepth * $baseHeight + $powdepth) . "px;\"";
		$str .= ">
<div class=\"treecol\">
<div class=\"treespacer\" style=\"height:" . (($powdepth * $baseHeight - $baseHeight) / 2) . "px;\"></div>
<div class=\"treematchholder\">
<div class=\"treeverthax\">

<div class=\"treematch\" onclick=\"getMatchForm(" . $data->getId() . ")\" >\n";
		//getForm(".$data->getId().")
		if ($data instanceof Model_Match) {
			$str .= "<div class=\"treematchinfo\">Match " . $data . "</div>\n";
			foreach ($data->getParticipantList() as $p) {
				if ($p instanceof Model_Participant) {
					$str .= "<div class=\"treeparticipant";
					$result = $p->getResult();
					if ($result > 0) {
						if ($result == 1) {
							$str .= " treewinner";
						} else {
							$str .= " treeloser";
						}
					}
					$str .= "\">\n";
					$str .= "<div class=\"treeparticipantname\">" . $p . "</div>\n";
					$str .= "<div class=\"treeparticipantscore\">";
					if ($p->hasResult()) {
						$str .= $p->getScore();
					}
					$str .= "</div>
</div>\n";
				}				
			}
		}
		$str .= "</div>
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
				$str .= $this->_stepTree($tree->left(), $treeDepth - 1, false, $baseHeight, $baseWidth);
			} else {
				$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 2) . "px;\"></div>\n";
			}
			if ($tree->right() !== NULL) {
				$str .= $this->_stepTree($tree->right(), $treeDepth - 1, false, $baseHeight, $baseWidth);
			} else {
				$str .= "<div class=\"treespacer\" style=\"height:" . ($powdepth * $baseHeight / 2) . "px;\"></div>\n";
			}
			$str .= "</div>\n";
		}
		$str .= "</div>\n";
		return $str;
	}
}
