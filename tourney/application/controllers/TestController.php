<?php

class TestController extends Zend_Controller_Action
{

	public function init()
	{
	}
	
	public function playersAction()
	{
		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/log/log.txt');
		$logger = new Zend_Log($writer);
		$logger->log('players called', 7);
		if ('ajax' != $this->_getParam('format', false)) {
			$logger->log('format not set to ajax, exiting', 7);
			return $this->_helper->redirector('index');
		}
		$logger->log('format set to ajax, returning json object', 7);
		$table = new Model_DbTable_User();
		$result = $table->fetchAll();
		$data = array();
		foreach ($result as $row) {
			$data[] = $row['name'];
		}
		$this->_helper->autoCompleteDojo($data);
	}

	public function tourneylistAction()
	{
		Zend_Debug::dump(Model_Type_Abstract::getTypeList());
		Zend_Debug::dump(Model_MatchupType_Abstract::getMatchupTypeList());
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
		$post = $this->getRequest()->getPost();
		// This action will create a tournament and then save it to the database
		$eggtourney = new Model_Type_SingleElimination(); // Make a new ladder tournament
		// Make a new type of game, and save it to the database to make it ready
		// Here we are creating and saving a new game type, but to load a game from the database say with the id of 1, you would do:
		// $game = new Model_Game(1);
		// OR
		// $game = new Model_Game();
		// $game->load(1);
		$game = new Model_Game();
		$game->setName("Test Game");
		$game->setDescription("This is a new type of game");
		$game->setScoringtype(new Model_VictoryCondition_HighestScore());
		$game->save(); // Will throw an exception if any one of name, description or scoringtype isn't set
		$eggtourney->setGame($game); // Set the game type of the tourney to this new game.  It's also possible to pass an integer to setGame equivalent to the game id in the database
		// Now we will set the matchup type for the tournament
		if (isset($post['typesubform']['matchuptype'])) {
			$eggtourney->setMatchuptype(new $post['typesubform']['matchuptype']);
		} else {
			$eggtourney->setMatchuptype(new Model_MatchupType_Random());
		}
		// Now make a list of participants for the tourney
		$participantlist = new Model_ParticipantList(); // Create a participant list so we can add participants to it.  A participant list is a fancy array.
		// You can make a participant out of anything!  As long as it implements Model_Participantable (lol)
		// Putting a string when creating a Model_User will load that user from the database.  If that model isn't there, it will throw an exception.
		if (isset($post['player'])) {
			foreach ($post['player'] as $player) {
				$participant = new Model_Participant();
				$user = new Model_User($player);
				$participant->set($user);
				$eggtourney->addParticipant($participant);
			}
		} else {
			$participant = new Model_Participant();
			$user = new Model_User('beefsack');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('baconheist');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('test1');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('test2');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('test3');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('test4');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('test5');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
			// Lets add one more
			$participant = new Model_Participant();
			$user = new Model_User('test6');
			$participant->set($user);
			$eggtourney->addParticipant($participant);
		}
		// Now we have a list of participants, a game, and a matchup type, lets save the tourney which will build it and save it to the database.
		$eggtourney->save();
		 
		$this->view->tourneyTree = $eggtourney->getTree();
		
		$this->view->headScript()->appendFile(PUBLIC_PATH . '/js/addPlayer.js');
		
		$form = $eggtourney->getForm();
		 
		if ($this->getRequest()->isPost()) {
			$postdata = $this->getRequest()->getPost();
			if ($form->isValid($postdata)) {
				// it was valid, in real life now we would probably save information in postdata to the database, then redirect to a new page
				//$this->_helper->redirector('index', 'index'); // Forwards to the index action of the index controller.  First arg for action, second for controller (optional)
				Zend_Debug::dump($postdata);
			}
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
