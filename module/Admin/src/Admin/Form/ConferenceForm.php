<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Checkbox;

class ConferenceForm extends Form
{
	public function __construct($name = null, $user_id = null) {
		parent::__construct($name);
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'well offset3 span5');
		
		// You'll see _() functions here. This is for the sake of "extractability" :)

		$this->add(array(
			'name' => 'user_id',
			'attributes' => array(
				'type' => 'hidden',
				'value' => $user_id,
			),
		));
		
		$this->add(array(
			'name' => 'name',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('Conference name'),
			),
		));

		$this->add(array(
			'name' => 'short_name',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('Conference name (short)'),
			),
		));
		
		$this->add(array(
			'name' => 'description',
			'attributes' => array(
				'type'  => 'textarea',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('Description'),
			),
		));
		
		$this->add(array(
			'name' => 'location',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('Location'),
			),
		));
		
		$this->add(array(
			'name' => 'address',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('Address'),
			),
		));
		
		$this->add(array(
			'name' => 'city',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('City'),
			),
		));
		
		$this->add(array(
			'name' => 'state',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('State'),
			),
		));
		
		$this->add(array(
			'name' => 'country',
			'attributes' => array(
				'type'  => 'text',
				'class'	=> 'span5',
			),
			'options' => array(
				'label' => _('Country'),
			),
		));
		
		$gmt = new Select('gmt', array('label' => _('GMT')));
		$gmt->setAttributes(
			array(
				'class' => 'span5',
				'options' => array(
					'-12.0' => '(GMT -12:00) Eniwetok, Kwajalein',
					'-11.0' => '(GMT -11:00) Midway Island, Samoa',
					'-10.0' => '(GMT -10:00) Hawaii',
					'-9.0'  => '(GMT -9:00) Alaska',
					'-8.0'  => '(GMT -8:00) Pacific Time (US &amp; Canada)',
					'-7.0'  => '(GMT -7:00) Mountain Time (US &amp; Canada)',
					'-6.0'  => '(GMT -6:00) Central Time (US &amp; Canada), Mexico City',
					'-5.0'  => '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima',
					'-4.0'  => '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz',
					'-3.5'  => '(GMT -3:30) Newfoundland',
					'-3.0'  => '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
					'-2.0'  => '(GMT -2:00) Mid-Atlantic',
					'-1.0'  => '(GMT -1:00 hour) Azores, Cape Verde Islands',
					'0.0'   => '(GMT) Western Europe Time, London, Lisbon, Casablanca',
					'1.0'   => '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris',
					'2.0'   => '(GMT +2:00) Kaliningrad, South Africa',
					'3.0'   => '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
					'3.5'   => '(GMT +3:30) Tehran',
					'4.0'   => '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
					'4.5'   => '(GMT +4:30) Kabul',
					'5.0'   => '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
					'5.5'   => '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi',
					'5.75'  => '(GMT +5:45) Kathmandu',
					'6.0'   => '(GMT +6:00) Almaty, Dhaka, Colombo',
					'7.0'   => '(GMT +7:00) Bangkok, Hanoi, Jakarta',
					'8.0'   => '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
					'9.0'   => '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
					'9.5'   => '(GMT +9:30) Adelaide, Darwin',
					'10.0'  => '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
					'11.0'  => '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
					'12.0'  => '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka',
				),
			)
		);
		$this->add($gmt);

		$this->add(
			new Checkbox(
				'show_running',
				array(
					'label' => _('Show alert when conference is running'),
				)
			)
		);

		$this->add(
			new Checkbox(
				'active',
				array(
					'label' => _('Active'),
				)
			)
		);

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'	=> 'submit',
				'id'	=> 'submitbutton',
				'value' => _('Save'),
				'class'	=> 'btn btn-primary btn-large span2',
			),
		));
	}
}