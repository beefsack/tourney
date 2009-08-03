<?php

class AjaxController extends Zend_Controller_Action
{

	public function init()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);    
	}
	
	public function savematchAction()
	{
		try {
		$matchid = $this->_getParam('matchid');
		$match = new Model_Match($matchid);
		$match->handleForm($this->_getAllParams());
		$match->save();
		} catch (Exception $e) {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
	
	public function treeAction()
	{
		$tourneyid = $this->_getParam('tourneyid');
		
		$tourney = Model_Type_Abstract::factory($tourneyid);
		
		if ($tourney instanceof Model_Interface_Tree) {
			echo $this->view->printTree($tourney->getTree(), true);
		} else {
			echo "Specified tournament does not have a tree";
		}
	}
	
	public function matchformAction()
	{
		$matchid = $this->_getParam('matchid');
		
		// first load the match and make sure that all the participants are decided.  If there is an empty participantid, then one of the participants is not yet known.
		$match = new Model_Match($matchid);
		
		$valid = true;
		foreach ($match->getParticipantList() as $p) {
			$valid = $valid && ($p->getParticipantId() == true);
		}
		
		if ($valid) {
			$form = new Form_ScoreInput;
			$form->setPlayers($matchid);
			
	        echo $form;
		} else {
			echo "One or more participants in this match is not yet known.";
		}
	}

	public function playersAction()
	{
		if ('ajax' != $this->_getParam('format', false)) {
			return $this->_helper->redirector('index', 'index');
		}
		$table = new Model_DbTable_User();
		$query = $table->select()
		->where('name like ?', str_replace('*', '%', $this->_getParam('name')))
		->limit(10)
		;
		$stmt = $query->query();
		$result = $stmt->fetchAll();
		$data = array();
		foreach ($result as $row) {
			$data[] = $row['name'];
		}
		$this->_helper->autoCompleteDojo($data);
	}

	public function gamesAction()
	{
		if ('ajax' != $this->_getParam('format', false)) {
			return $this->_helper->redirector('index', 'index');
		}
		$table = new Model_DbTable_Game();
		$query = $table->select()
		->where('name like ?', str_replace('*', '%', $this->_getParam('name')))
		->limit(10)
		;
		$stmt = $query->query();
		$result = $stmt->fetchAll();
		$games = array();
		foreach ($result as $row) {
			$games[] = array(
			'name' => $row['name'],
			'id' => $row['id'],
			);
		}
		$data = array(
			'identifier' => 'id',
			'label' => 'name',
			'items' => $games,
		);
		
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
		$layout = Zend_Layout::getMvcInstance();
		if ($layout instanceof Zend_Layout) {
			$layout->disableLayout();
		}

		$response = Zend_Controller_Front::getInstance()->getResponse();
		$response->setHeader('Content-Type', 'application/json');
		$response->setBody(Zend_Json::encode($data));
		$response->sendResponse();
		exit;
	}
}
