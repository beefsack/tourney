<?php

class Form_TypeSelect extends Zend_Form
{
	public function init()
	{
		$this->setMethod('get');
		
		$element = new Zend_Dojo_Form_Element_FilteringSelect('type');
		$element->setLabel('Tournament Type');
		$element->setRequired(true);
		$element->addMultiOptions(Model_Type_Abstract::getTypeList());
		$this->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$element->setLabel('Submit');
		$this->addElement($element);
	}
}
