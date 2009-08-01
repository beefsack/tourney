<?php

class Model_Type_Ladder extends Model_Type_Abstract implements Model_Ladderable
{
	// The ladder info for this object
	protected $_ladder;
	
	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_buildTourney()
	 */
	protected function _buildTourney()
	{
		if ($this->_dirty) {
			$this->_matchList->clearMatchList();
			// Rebuild the tournament from scratch
			foreach ($this->_participantList as $participant1)
			{
				foreach ($this->_participantList as $participant)
				{
					if($participant1!==$participant)
					{
						$match = new Model_Match();


						$match->addParticipant($participant1);

						$match->addParticipant($participant);

						$match->setGame($this->getGame());

						$this->_matchList->addMatch($match);
					}
						
				}
				$this->_dirty = false;
				//Zend_Debug::dump($this);
				
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#_typeSpecificForm($form)
	 */
	protected function _typeSpecificForm(Zend_Form $form) {

	}

	/**
	 * (non-PHPdoc)
	 * @see models/Model_Ladderable#getLadder()
	 */
	public function getLadder()
	{
		$array = array();
		$this->_buildTourney();
		foreach ($this->getMatchList() as $match)
		{
			if($match instanceof Model_Match)
			{
				foreach($match->getParticipantList() as $player)
				if($player instanceof Model_Participant)
				{


						$array[(string)$player]['Name']=(string)$player;
						$array[(string)$player]['played']++;
						$array[(string)$player]['wins']++;
						$array[(string)$player]['loss']++;

				}

					
			}
		}

		$ladder = new Model_LadderType();
		foreach($array as $key=>$row)
		{
			$ladder->insertRow($row);
		}


		return $ladder;
	}

	/**
	 * (non-PHPdoc)
	 * @see models/Type/Model_Type_Abstract#getTypeName()
	 */
	public function getTypeName()
	{
		return "Ladder";
	}
}