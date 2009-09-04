<?php
class TourneyController extends Zend_Controller_Action
{
	public function viewAction()
	{
		$tourneyid = $this->_getParam('id');
		if ($tourneyid) {
			$tourney = Model_Type_Abstract::factory($tourneyid);
			$this->view->name = $tourney->getName();
			if ($tourney instanceof Model_Interface_Tree) {
				$this->view->tourneyTree = $tourney->getTree();
			}
			if ($tourney instanceof Model_Interface_Ladder) {
				$this->view->tourneyLadder = $tourney->getLadder();
			}
		}
	}

}