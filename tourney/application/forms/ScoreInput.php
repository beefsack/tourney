<?php



class Form_ScoreInput extends Zend_Form
{
	
	public function setPlayers($id)
	{
		$this->clearElements();
		
		$curMatch = new Model_Match($id);
		
		foreach($curMatch->getParticipantList() as $player)
		{
		$element = new Zend_Dojo_Form_Element_NumberTextBox($player->getParticipantId());
		$element->setLabel($player->getParticipantId());
		
		$element->setRequired(true);
		$this->addElement($element);
	
		}
		
		$element = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$element->setLabel('Submit');
		$this->addElement($element);

	}
	
	public function init()
	{
		
		$this->setMethod('post');
		

	}
}
