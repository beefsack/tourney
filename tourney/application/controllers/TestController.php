<?php

    class bacon
    {
    	protected $_data;
    	
    	function bacon(&$data = NULL)
    	{
    		if ($data !== NULL) {
    			$this->steak($data);
    		}
    	}
    	
    	function steak(&$thing)
    	{
    		echo "value of thing is $thing<br />";
    		$this->_data =& $thing;
    		echo "value of data is $this->_data<br />"; 
    	}
    	
    	function __toString()
    	{
    		echo "returning thing value ".$this->_data."<br />";
    		return (string) $this->_data;
    	}
    }
    
class TestController extends Zend_Controller_Action
{

    public function init()
    {
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
    	$eggtourney->setMatchuptype(new Model_MatchupType_Random());
    	// Now make a list of participants for the tourney
    	$participantlist = new Model_ParticipantList(); // Create a participant list so we can add participants to it.  A participant list is a fancy array.
    	// You can make a participant out of anything!  As long as it implements Model_Participantable (lol)
    	// Putting a string when creating a Model_User will load that user from the database.  If that model isn't there, it will throw an exception.
    	$participant = new Model_Participant();
    	$user = new Model_User('beefsack');
    	$participant->set($user);
    	$eggtourney->addParticipant($participant);
    	// Lets add one more
    	$user = new Model_User('baconheist');
    	$participant->set($user);
    	$eggtourney->addParticipant($participant);
    	// Now we have a list of participants, a game, and a matchup type, lets save the tourney which will build it and save it to the database.
    	$eggtourney->save();
    }
    
    public function treereftestAction()
    {
    	echo (pow(2, ceil(log(29, 2)))) . "<br />";
    	for ($i = 1; $i < 64; $i++) {
    		echo $i . ":" . ceil(log($i, 2)) . ":" . pow(2, ceil(log($i, 2))) . "<br />";
    	}
    	// first test
    	//$egg = new bacon();
    	$dog = 2;
    	//$egg->steak($dog);
    	$egg = new bacon($dog);
    	$dog = 5;
    	echo "$egg<br />";
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
    }
}
