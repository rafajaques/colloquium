<?php

namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface
{
	public $id;
	public $username;
	public $password;
	public $name;
	public $email;
	public $valid;
	public $validation_key;
	
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id				= (isset($data['id'])) ? $data['id'] : null;
		$this->username			= (isset($data['username'])) ? $data['username'] : null;
		$this->password			= (isset($data['password'])) ? $data['password'] : null;
		$this->name				= (isset($data['name'])) ? $data['name'] : null;
		$this->email			= (isset($data['email'])) ? $data['email'] : null;
		$this->validation_key	= (isset($data['validation_key'])) ? $data['validation_key'] : null;
		$this->valid			= (isset($data['valid'])) ? $data['valid'] : null;
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	public function getInputFilter()
	{	
		if (!$this->inputFilter) {
			$inputFilter       = new InputFilter();
			$factory           = new InputFactory();
			
			// username
			$inputFilter->add($factory->createInput(array(
				'name'				=> 'username',
				'required'			=> true,
				'filters'			=> array(
					array('name'	=> 'StripTags'),
					array('name'	=> 'StringTrim'),
				),
				'validators'		=> array(
					array(
						'name'		=> 'StringLength',
						'options'	=> array(
							'encoding'	=> 'UTF-8',
							'min'		=> 1,
							'max'		=> 200,
						),
					),
				),
			)));
			
			// password
			$inputFilter->add($factory->createInput(array(
				'name'				=> 'password',
				'required'			=> true,
				'filters'			=> array(
					array('name'	=> 'StripTags'),
					array('name'	=> 'StringTrim'),
				),
				'validators'		=> array(
					array(
						'name'		=> 'StringLength',
						'options'	=> array(
							'encoding'	=> 'UTF-8',
							'min'		=> 1,
						),
					),
				),
			)));
			
			// name
			$inputFilter->add($factory->createInput(array(
				'name'				=> 'name',
				'required'			=> true,
				'filters'			=> array(
					array('name'	=> 'StripTags'),
					array('name'	=> 'StringTrim'),
				),
				'validators'		=> array(
					array(
						'name'		=> 'StringLength',
						'options'	=> array(
							'encoding'	=> 'UTF-8',
							'min'		=> 5,
							'max'		=> 200,
						),
					),
				),
			)));
			
			// email
			$inputFilter->add($factory->createInput(array(
				'name'				=> 'email',
				'required'			=> true,
				'filters'			=> array(
					array('name'	=> 'StripTags'),
					array('name'	=> 'StringTrim'),
				),
				'validators'		=> array(
					array(
						'name'		=> 'StringLength',
						'options'	=> array(
							'encoding'	=> 'UTF-8',
							'min'		=> 5,
							'max'		=> 200,
						),
					),
				),
			)));

			$this->inputFilter = $inputFilter;
		}
	
		return $this->inputFilter;
	}
}