<?php

namespace Cfp\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;

class SubmissionForm extends Form
{
	public function __construct($name = null, $tracks = array()) {
		parent::__construct('submission');

		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'well offset3 span6');

		// You'll see _() functions here. This is for the sake of "extractability" :)

		$this->add(array(
			'name' => 'title',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span',
			),
			'options' => array(
				'label' => _('Title'),
			),
		));
		
		$track = new Select('track_id', array('label' => _('Track')));
		$track->setAttributes(
			array(
				'class' => 'span',
				'options' => $tracks,
			)
		);
		$this->add($track);
		
		$this->add(array(
			'name' => 'abstract',
			'attributes' => array(
				'type'  => 'textarea',
				'class'	=> 'span',
				'rows'	=> '5',
			),
			'options' => array(
				'label' => _('Abstract'),
			),
		));
		
		$this->add(array(
			'name' => 'minicurriculo',
			'attributes' => array(
				'type'  => 'textarea',
				'class'	=> 'span',
				'rows'	=> '5',
			),
			'options' => array(
				'label' => _('Mini-curriculo do palestrante'),
			),
		));
		
		$duration = new Select('duration', array('label' => _('Duration')));
		$duration->setAttributes(
			array(
				'class' => 'span',
				'options' => array(
					'1' => '1h',
					'2' => '2h',
					'3' => '3h',
					'4' => '4h+',
				),
			)
		);
		$this->add($duration);


		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'	=> 'submit',
				'id'	=> 'submitbutton',
				'class'	=> 'btn btn-primary btn-large span',
				'value'	=> _('Submit'),
			),
		));
	}
}