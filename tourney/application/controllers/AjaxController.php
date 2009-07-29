<?php

class AjaxController extends Zend_Controller_Action
{

	public function init()
	{
	}

	public function playersAction()
	{
		//$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/log/log.txt');
		//$logger = new Zend_Log($writer);
		//$logger->log('players called', 7);
		if ('ajax' != $this->_getParam('format', false)) {
			//$logger->log('format not set to ajax, exiting', 7);
			return $this->_helper->redirector('index', 'index');
		}
		//$logger->log('format set to ajax, returning json object', 7);
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
		//$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/log/log.txt');
		//$logger = new Zend_Log($writer);
		//$logger->log('players called', 7);
		if ('ajax' != $this->_getParam('format', false)) {
			//$logger->log('format not set to ajax, exiting', 7);
			return $this->_helper->redirector('index', 'index');
		}
		//$logger->log('format set to ajax, returning json object', 7);
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
