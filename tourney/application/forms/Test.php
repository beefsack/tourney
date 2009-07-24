<?php

class Form_Test extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		
		$element = new Zend_Form_Element_Text('username');
		$element->setLabel('User Name');
		$element->addValidator(new Zend_Validate_Alnum());
		$element->addValidator(new Zend_Validate_StringLength(3, 32));
		$element->setRequired(true);
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Password('password');
		$element->setLabel('Password');
		$element->addValidator(new Zend_Validate_StringLength(6));
		$element->setRequired(true);
		$this->addElement($element);
		
		$element = new Zend_Form_Element_Submit('submit');
		$element->setLabel('Submit');
		$this->addElement($element);
	}
}
