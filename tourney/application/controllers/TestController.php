<?php

class TestController extends Zend_Controller_Action
{

	public function init()
	{
	}
	
	public function tourneylistAction()
	{
		Zend_Debug::dump(Model_Type_Abstract::getTypeList());
		Zend_Debug::dump(Model_MatchupType_Abstract::getMatchupTypeList());
	}
	
	public function adduserAction()
	{
		$form = new Form_User();
		if ($post = $this->getRequest()->getPost()) {
			if ($form->isValid($post)) {
				$data['name'] = $post['name'];
				$data['password'] = sha1($post['password']);
				$table = new Model_DbTable_User();
				$table->insert($data);
				$this->_helper->redirector('index', 'index'); // Forwards to the index action of the index controller.  First arg for action, second for controller (optional)
			}
		}
		$this->view->form = $form;
	}
	
	public function addgameAction()
	{
		$form = new Form_Game();
		if ($post = $this->getRequest()->getPost()) {
			if ($form->isValid($post)) {
				$data['name'] = $post['name'];
				$data['description'] = $post['description'];
				$data['scoringtype'] = $post['scoringtype'];
				$table = new Model_DbTable_Game();
				$table->insert($data);
				$this->_helper->redirector('index', 'index'); // Forwards to the index action of the index controller.  First arg for action, second for controller (optional)
			}
		}
		$this->view->form = $form;
	}

	public function indexAction()
	{
		$this->view->headTitle("YA MUM");
		$dataObject1 = new Model_TourneyData("egg:yolk:banana;banana:split;");
		$dataObject = new Model_TourneyData($dataObject1);
		$dataObject['fart'] = 'bacon';
		$dataObject['smeg'] = 'chortle';
		$dataObject['boobs'] = 'hat';
		$this->view->dataObject = $dataObject;
		$egg = new Model_DbTable_Game();
		$insertdata['name'] = 'egg';
		$insertdata['description'] = 'this game is an egg';
		$insertdata['scoringtype'] = 'highestscore';
		//$egg->insert($insertdata); // Removed this cos we don't want database spam
	}

	public function createandsavetournamentAction()
	{
		$type = (string) $this->_getParam('type', false);
		if ($type) {
			$post = $this->getRequest()->getPost();
			// This action will create a tournament and then save it to the database
			$eggtourney = new $type; // Make a new ladder tournament
			if ($eggtourney instanceof Model_Type_Abstract) {
				$form = $eggtourney->getForm();
				if ($post && $form->isValid($post)) {
					// Let the tourney object take the data
					$eggtourney->handleForm($post);
					
					// Now we have a list of participants, a game, and a matchup type, lets save the tourney which will build it and save it to the database.
					if ($this->_getParam('save', '') == 'true') {
						$eggtourney->save();
					}
		
					if ($eggtourney instanceof Model_Treeable) {
						$this->view->tourneyTree = $eggtourney->getTree();
					}
		
					if ($eggtourney instanceof Model_Ladderable) {
						$this->view->tourneyLadder = $eggtourney->getLadder();
					}
					
				}

				//Zend_Debug::dump($eggtourney);
				
				$this->view->headScript()->appendFile(PUBLIC_PATH . '/js/addPlayer.js');
				
				/*if ($this->getRequest()->isPost()) {
					$postdata = $this->getRequest()->getPost();
					if ($form->isValid($postdata)) {
						// it was valid, in real life now we would probably save information in postdata to the database, then redirect to a new page
						//$this->_helper->redirector('index', 'index'); // Forwards to the index action of the index controller.  First arg for action, second for controller (optional)
						Zend_Debug::dump($eggtourney);
					}
				}*/
			}
		}
		if (!$form) {
			$form = new Form_TypeSelect();
		}
		$this->view->form = $form;
	}

	public function createandsavegameAction()
	{
		$newgame = new Model_Game(2);
		echo $newgame->getId();
	}
	 
	public function createandsavematchAction()
	{
		 
		$newmatch = new Model_MatchList(2);
		//echo $newmatch->getGameid();
		//echo $newmatch->getData(0);
	}

	public function makeladderAction()
	{
		 $newladder=new Model_LadderType();
		 $newladder->insertRow(array('egg'=>'is tasty', 'trashcan'=>'is not'));
		 $newladder->insertRow(array('egg'=>'is tastyER', 'trashcandfdfd'=>'is not', 'trashcandfdsssssssssssfd'=>'is not'));
		 $newladder->getColumnTitles();
		 $this->view->ladder=$newladder;
	}
	
	public function treereftestAction()
	{
		echo (pow(2, ceil(log(29, 2)))) . "<br />";
		for ($i = 1; $i < 64; $i++) {
			echo $i . ":" . ceil(log($i, 2)) . ":" . pow(2, ceil(log($i, 2))) . "<br />";
		}
		// second test
		$toss = new Model_MatchList();
		$fart = new Model_Match();
		$fart->setData('somedata', 'somevalue');
		echo "initial value of somedata is ". $fart->getData('somedata') ."<br />";
		$fart2 = new Model_Match();
		$fart2->setData('fart', $fart);
		$fart->setData('somedata', 'this is a new value');
		echo $fart2->getData('fart')->getData('somedata')."<br />";
		$fart3 = $fart2;
		$fart3->setData('1','2');
		echo $fart2->getData('1')."<br />";
		 
	}

	public function loadtournamentAction()
	{
		// Test load tournament
	}
}
