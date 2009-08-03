<?php

class Model_Type_DoubleElimination extends Model_Type_SingleElimination
{
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_buildTourney()
	 */
	protected function _buildTourney()
	{
		if ($this->_dirty) {
			$this->_matchList->clearMatchList();
			$this->_tree = new Model_TreeType();
			/*
			 * One of the big pieces of code for this project for the most complicated of the two tree based structures
			 * There are two options, one would to be to just store the matches in a TreeType object, the other would to store it both in a TreeType and a MatchList
			 * I think the best option would be to use a TreeType as main storage, and whenever _buildTourney is called the MatchList is repopulated with the games
			 * It is important to keep the MatchList because the view can use the MatchList to display a list of matches.
			 * The structure of a Double Elimination tournament is quite complex, as in reality it is a combination of two trees.
			 * The first tree (we can call it the left tree) is a standard knockout tree the same as in SingleElimination and can have a MatchupType applied.
			 * The second tree (the right tree) is a loser tree, where a team will move if it loses a game in the left tree
			 * So:
			 * A loss in the left tree means you move to the right tree
			 * A loss in the right tree means you are eliminated
			 * Hence every team gets a second chance, and the final is played between the winner of the left tree and the winner of the loser tree
			 * This adds complexity to the data because a player can jump from one tree to the other.
			 * This is actually the reason why I wanted to make a data field for participants.  
			 * We could use the data field to store two values, a source and a sourcetype.
			 * The source is the id of the match that the player will come from.
			 * The sourcetype will be 'winner' or 'loser'
			 * Before there is an id for a match, probably a reference to the match object can be stored.  After that match is saved then the id can be extracted from the reference
			 * This means all saving should be done from the tree, in a bottom up fashion.  All lower nodes need to be saved before any higher nodes can be saved
			 * In a similar fashion, because the right tree depends on the left tree, the left tree has to be saved before the right tree.
			 * PHP makes this a lot easier because, like Java, if you do oneObject = anotherObject it always assigns by reference not copy.  So you can have a match in both the winner tree and the loser tree, and when it saves the ID will be available from both sides. 
			 */
			// First, get the matchups from the matchup object
			if (($matchupObj = $this->_getMatchupObject()) !== NULL) {
				$matchups = $matchupObj->getMatchups();
			} else {
				throw new Exception("Unable to instantiate matchup object of type ".$this->getMatchuptype());
			}
			// Now build the match tree, which is done recursively
			$winnertree = $this->_createTree($matchups);
			
			// Build the loser tree from the winner tree
			
			
			$this->_dirty = false;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#getTypeName()
	 */
	public function getTypeName()
	{
		return "Double Elimination";
	}
}