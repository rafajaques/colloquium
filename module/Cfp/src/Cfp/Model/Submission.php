<?php

namespace Cfp\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Submission implements InputFilterAwareInterface
{
	protected $inputFilter;
	
	public $submission_id;
	public $conference_id;
	public $track_id;
	public $title;
	public $abstract;
	public $duration;
	public $date_sent;
	public $accepted;
	/**
	 * @TODO remove this
	 */
	public $minicurriculo;
	
	public function exchangeArray($data)
	{
		/**
		 * @TODO make this better
		 */
		$extract = array(
			'submission_id', 'conference_id', 'track_id', 'title', 'abstract',
			'duration', 'date_sent', 'accepted',
			// @TODO remove this
			'minicurriculo',
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
				'name'     => 'title',
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
				'name'     => 'track_id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'abstract',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'minicurriculo',
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
						),
					),
				),
			)));
			
			$inputFilter->add($factory->createInput(array(
				'name'     => 'duration',
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