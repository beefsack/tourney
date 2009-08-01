<?php

class Form_User extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		
		$element = new Zend_Dojo_Form_Element_TextBox('name');
		$element->setLabel('Name');
		$element->setRequired(true);
		$this->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_PasswordTextBox('password');
		$element->setLabel('Password');
		$element->setRequired(true);
		$element->addValidator(new Zend_Validate_StringLength(6));
		$this->addElement($element);
		
		$element = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$element->setLabel('Submit');
		$this->addElement($element);
	}
}
