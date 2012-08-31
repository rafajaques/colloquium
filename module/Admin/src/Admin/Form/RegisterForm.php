<?php

namespace Admin\Form;

use Zend\Form\Form;

class RegisterForm extends Form
{
	public function __construct($name = null) {
		// we want to ignore the name passed
		parent::__construct('register');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'well offset3 span5');

		$this->add(array(
			'name' => 'username',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
		));
		
		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type'  => 'password',
				'class'	=> 'span5',
			),
		));

		$this->add(array(
			'name' => 'name',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
		));
		
		$this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
		));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'	=> 'submit',
				'id'	=> 'submitbutton',
				'class'	=> 'btn btn-primary btn-large span2',
			),
		));
	}
}