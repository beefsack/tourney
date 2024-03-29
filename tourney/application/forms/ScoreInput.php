<?php



class Form_ScoreInput extends Zend_Dojo_Form
{

	public function setPlayers($id)
	{
		$this->clearElements();

		$curMatch = new Model_Match($id);
		
		foreach($curMatch->getParticipantList() as $p)
		{
			$element = new Zend_Dojo_Form_Element_NumberTextBox($p->getId() . 'score');
			$element->setLabel($p);
			if ($score = $p->getScore()) {
				$element->setValue($score);
			}
			$element->setRequired(true);
			$this->addElement($element);
		}

		$element = new Zend_Form_Element_Hidden('tourneyid');
		$element->setValue($curMatch->getTourneyid());
		$element->setDecorators(array(array('ViewHelper')));
		$this->addElement($element);

		$element = new Zend_Form_Element_Hidden('matchid');
		$element->setValue($curMatch->getId());
		$element->setDecorators(array(array('ViewHelper')));
		$this->addElement($element);

		$element = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$element->setLabel('Submit');
		$this->addElement($element);
		
		$this->setAttrib('onSubmit', 'return saveMatchForm()');

	}

	public function init()
	{
		$this->setAttrib('id', 'scoreinputform');
		$this->setMethod('post');


	}
}
