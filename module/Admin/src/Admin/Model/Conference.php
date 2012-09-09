<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Conference implements InputFilterAwareInterface
{
	protected $inputFilter;

	public function exchangeArray($data)
	{
		/**
		 * @TODO make this better
		 */
		$extract = array(
			'conference_id', 'user_id', 'name', 'short_name', 'description', 'registration_fee', 'location',
			'address', 'city', 'state', 'country', 'gmt', 'show_running', 'active'
		);

		foreach ($extract as $ext)
			$this->$ext = (isset($data[$ext])) ? $data[$ext] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name'     => 'user_id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'name',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 255,
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'short_name',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'description',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'registration_fee',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'location',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 255,
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'address',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'city',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'state',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'country',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 1,
							'max'      => 150,
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'gmt',
				'required' => true,
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'show_running',
				'required' => false,
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'active',
				'required' => false,
			)));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}