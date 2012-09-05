<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ExtractDate' => 'Helpers\View\Helper\ExtractDate',
        ),
    ),
	
	'router' => array(
		'routes' => array(
			/* JS Helpers */
			'jshelper' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/js/helpers[/]',
					'defaults' => array(
						/**
						 * @TODO This shouldn't be a valid route
						 */
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					/**
					 * List of speakers
					 */
					'speakers' => array(
						'type' => 'segment',
						'options' => array(
							'route' => 'speakers/list/:id[/]',
							'defaults' => array(
		                        'controller' => 'Application\Controller\MySubmissions',
		                        'action'     => 'jsSpeakersList',
							),
                            'constraints' => array(
								'id'		=> '[0-9]*',
                            ),
						),
					),
					/**
					 * Add speaker
					 */
					'speakers_add' => array(
						'type' => 'segment',
						'options' => array(
							'route' => 'speakers/add[/]',
							'defaults' => array(
		                        'controller' => 'Application\Controller\MySubmissions',
		                        'action'     => 'jsSpeakersAdd',
							),
						),
					),
					/**
					 * Check email
					 */
					'email' => array(
						'type' => 'segment',
						'options' => array(
							'route' => 'email/check/:email',
							'defaults' => array(
		                        'controller' => 'Application\Controller\MySubmissions',
		                        'action'     => 'jsMailCheck',
							),
						),
					),
				),
			),	
		),
	),
);
