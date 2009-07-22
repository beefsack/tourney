<?php

class Model_Type_SingleElimination extends Model_Type_Abstract implements Model_Treeable
{
	// The tree for the elimination
	protected $_tree;
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_buildTourney()
	 */
	protected function _buildTourney()
	{
		if ($this->_dirty) {
			$this->_matchList->clearMatchList();
			$this->_tree = new Model_TreeType();
			// Rebuild the tournament from scratch
			// @todo write _buildTourney for SingleElimination
			/*
			 * A little bit tricky, this builds the match $_tree
			 * Just like saving, the initial building of the tree needs to be done bottom up
			 * That is, because games higher up in the tree depend on results lower down to know who the participants will be, the bottom nodes need to be done first
			 * Everything would be easy if you forced the amount of players to be a power of 2, making a balanced tree (2, 4, 8, 16 etc)
			 * But that is very restrictive.  If someone really wants to make a tourney with a different player count, they shouldn't be restricted by the system
			 * The ability to create and process unbalanced trees is pretty important imo
			 * This will be twofold, one will be a requirement that the MatchupType returns matchups in a useful structure that this function can build an unbalanced tree with
			 * The other requirement, of course, will be that this function can generate unbalanced trees
			 * Tree generation can be simple, thanks to the matchup object giving us a nice array of participants
			 * The matchup object will return a 2d array like this:
			 * array[0] { array[0]: player1; array[1]: player2 }
			 * array[1] { array[0]: player3; array[1]: player4 }
			 * array[2] { array[0]: player5; array[1]: player6 }
			 * array[3] { array[0]: player7; array[1]: player8 }
			 * This array here (8 players) will build a balanced game tree cos 8 is a power of 2
			 * The trick to generating a tree like this is simple, you keep splitting the array in half, and send one half down the left hand side and the other down the right
			 * So we split this in half and we have the root node with two branches
			 * 
			 *  / array[0 - 1]
			 * *
			 *  \ array[2 - 3]
			 *  
			 *  There can be another layer of splitting, producing a final tree like this
			 *  
			 *    / array[0]
			 *   *
			 *  / \ array[1]
			 * * 
			 *  \ / array[2]
			 *   *
			 *    \ array[3]
			 *    
			 * Once it hits the bottom level, it should create a match at each node with each of the participants, and then on the way back up to the root each node should have a match created with participants linking to the lower level using source and sourcetype
			 * 
			 * So for pseudocode:
			 * 
			 * createTree(array matchupArray) {
			 *   Check the length of matchupArray
			 *   if matchupArray only has one item, this node is a leaf {
			 *     If the one item of matchupArray is an array itself, and it has more than one participant inside (which means we have a leaf match) {
			 *       create a Model_Match
			 *       set the Model_Match participants to each of the participants in matchupArray[0]
			 *       add the Model_Match to the match list so we can output the list of matches if need be
			 *       create a Model_TreeType (the node to store the match in)
			 *       set the Model_TreeType data to the Model_Match just created
			 *       return the Model_TreeType (which is holding our fresh Model_Match)
			 *     } else if matchupArray is an array, however it only holds one participant (a possibility in unbalanced trees) {
			 *       return the Model_Participant in the array, let the parent call just add it to the match it already has
			 *     } else (an empty bracket, no participants) {
			 *       something is fucked, throw an exception "No participants in bracket"
			 *     }
			 *   } else if matchupArray has more than one item (we need to break it up more!) {
			 *     create a Model_TreeType for this node
			 *     split that array in half
			 *     send the first half to a new createTree, and store the return value with the new Model_TreeType->setLeft
			 *     send the second half to a ne wcreateTree, and store the return value with the new Model_TreeType->setRight
			 *     Create a Model_Match for this node
			 *     Create a new Model_Participant, setting data 'source' to the match created down the left branch, 'sourcetype' to 'winner'
			 *     Create a new Model_Participant, setting data 'source' to the match created down the right branch, 'sourcetype' to 'winner'
			 *     Add both these participants to the match created
			 *     Set the data of this node to the match created
			 *     Add the match to the matchList of the tourney
			 *     Return this TreeType node
			 *   } else {
			 *     This matchupArray is empty! Throw an exception something is rooted
			 *   }
			 * }
			 * 
			 * at the end of all that we will have returned a TreeType which is the root node of the tournament tree.  That should be saved to $this->_tree for storage
			 * 
			 * I reckon we should make the game in the root node have an extra piece of data in the dataObject saying nodetype:root; or something.
			 * Just having that makes the root node easy to find for the load function, cos the load function can start with the root and cascade down to make it easier
			 */
			// First, get the matchups from the matchup object
			if (($matchupObj = $this->_getMatchupObject()) !== NULL) {
				$matchups = $matchupObj->getMatchups();
			} else {
				throw new Exception("Unable to instantiate matchup object of type ".$this->getMatchuptype());
			}
			// Now build the match tree, which is done recursively
			$this->_tree = $this->_createTree($matchups);
			$this->_dirty = false;
		}
	}
	
	protected function _createTree(array $matchups)
	{
		// Make a node for this point in the tree
		$node = new Model_TreeType();
		// Make the match for this node and set it
		$match = new Model_Match();
		$node->setData($match);
		// Find out how many matchups are left, and handle differently for 1 or more
		$num = count($matchups);
		if ($num > 1) {
			// There is more than 1 matchup so we will need to recurse
			$splitPoint = ceil($num / 2);
			// First recurse the left subtree
			$left = $this->_createTree(array_slice($matchups, 0, $splitPoint));
			if ($left instanceof Model_TreeType) {
				// Left is a node, add it as a child of this node and set up participants
				$node->setLeft($left);
				$participant = new Model_Participant();
				$participant->setData('source', $left->data());
				$participant->setData('sourcetype', 'winner');
				$match->addParticipant($participant);
			} elseif ($left instanceof Model_Participant) {
				// Left is a participant, so just add it to this match
				$match->addParticipant($left);
			} else {
				throw new Exception("_createTree returned value which is neither Model_TreeType or Model_Participant");
			}
			// Then recurse the right subtree in the same way
			$right = $this->_createTree(array_slice($matchups, $splitPoint));
			if ($right instanceof Model_TreeType) {
				// Right is a node, add it as a child of this node and set up participants
				$node->setRight($right);
				$participant = new Model_Participant();
				$participant->setData('source', $right->data());
				$participant->setData('sourcetype', 'winner');
				$match->addParticipant($participant);
			} elseif ($right instanceof Model_Participant) {
				// Right is a participant, so just add it to this match
				$match->addParticipant($right);
			} else {
				throw new Exception("_createTree returned value which is neither Model_TreeType or Model_Participant");
			}
			// Now this node is set up, we return it
			return $node;
		} elseif ($num == 1) {
			// There is one matchup which makes a match.
			// Now we need to check if there is one or more participant, because each case is handled differently
			foreach ($matchups as $m) { // This will only run once, but we need to use it because we don't know the key of the array item we need
				$num = count($m);
				if ($num > 1) {
					// Go through each participant in the matchup and add it to the match
					foreach ($m as $p) {
						$match->addParticipant($p);
					}
					return $node;
				} elseif ($num == 1) {
					// Because this matchup only has one participant, we automatically move it up the tree
					unset($match);
					unset($node);
					return $m[0];
				} else {
					throw new Exception("Found empty matchup");
				}
			}
		} else {
			throw new Exception("_createTree called with empty array");
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_loadMatches()
	 */
	protected function _loadMatches()
	{
		// @todo write _loadMatches for SingleElimination
		/*
		 * @todo write a spam
		 */
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_saveMatches()
	 */
	protected function _saveMatches()
	{
		$this->_buildTourney();
		// @todo write _saveMatches for SingleElimination
		/*
		 * Instead of just saving the list of matches like is standard for tourneys, elimination style tournaments have a complex structure
		 * Because a match node in the tree depends on the result of the matches below it, the tree needs to be saved from the bottom up.
		 * If the $_dataObject['source'] is a Model_Match object, it will save the source as the ->getId() of that object
		 * So the pseudocode will be something like
		 * Call saverecursivefunction on the root node
		 * saverecursivefunction {
		 *   if left branch isn't null, call saverecursivefunction on the left branch (bottom up, each branch is called before doing this node)
		 *   if the right branch isn't null, call the saverecursivefunction on the right branch
		 *   if $_dataObject['source'] is a Model_Match object, set $_dataObject['source'] to that Model_Match->getId()
		 *   Save the match in this node
		 * }
		 * 
		 * Part of this function (after it is complete) is to check the database after saving the games to make sure there aren't any extra games in the db that were there from a previous save.
		 * This is easy, cos buildTourney also populates the matchlist
		 * So we can write an sql select all matches from a tourney that don't exist in the matchList, and delete em
		 */
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Model_Treeable#getTree()
	 */
	public function getTree()
	{
		$this->_buildTourney();
		return $this->_tree;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#getTypeName()
	 */
	public function getTypeName()
	{
		return "Single Elimination";
	}
}