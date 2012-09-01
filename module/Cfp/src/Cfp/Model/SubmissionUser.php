<?php

namespace Cfp\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class SubmissionUser implements InputFilterAwareInterface
{
	protected $inputFilter;
	
	public $submission_id;
	public $user_id;
	public $main;
	
	public function exchangeArray($data)
	{
		/**
		 * @TODO make this better
		 */
		$extract = array(
			'submission_id', 'user_id', 'main',
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
				'name'     => 'submission_id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name'     => 'user_id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));
		
			$inputFilter->add($factory->createInput(array(
				'name'     => 'main',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}