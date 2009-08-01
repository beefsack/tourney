<?php

class Form_Game extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		
		$element = new Zend_Dojo_Form_Element_TextBox('name');
		$element->setLabel('Name');
		$element->setRequired(true);
		$element->addValidator(new Zend_Validate_StringLength(3));
		$this->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_Textarea('description');
		$element->setLabel('Description');
		$element->setAttrib('style','min-height:128px;');
		$this->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_FilteringSelect('scoringtype');
		$element->setLabel('Scoring Type');
		$element->setRequired(true);
		$element->addMultiOptions(Model_VictoryCondition_Abstract::getTypeList());
		$this->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$element->setLabel('Submit');
		$this->addElement($element);
	}
}
