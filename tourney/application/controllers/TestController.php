<?php

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
    
    public function loadtournamentAction()
    {
    }
}
