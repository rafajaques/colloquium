<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UserRoleLinker implements InputFilterAwareInterface
{
	public function exchangeArray($data)
	{
		/**
		 * @TODO make this better
		 */
		$extract = array(
			'user_id', 'role_id',
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
				'name'     => 'role_id',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}