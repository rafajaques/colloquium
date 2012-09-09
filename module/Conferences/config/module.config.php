<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Conferences\Controller\Index' => 'Conferences\Controller\IndexController',
		),
	),
	'view_manager' => array(
		'template_path_stack' => array(
			'conferences' => __DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
	'router' => array(
        'routes' => array(
			'conferences' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/conferences[/]',
					'defaults' => array(
						'controller' => 'Conferences\Controller\Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'js' => array(
						'type' => 'segment',
						'options' => array(
							'route' => 'jsCalendar[/]',
							'defaults' => array(
								'controller' => 'Conferences\Controller\Index',
								'action' => 'jsCalendar',
							),
						),
					),
                    'inner' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[:action[/:id][/:done]][/]',
                            'constraints' => array(
                                'action'	=> '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'		=> '[0-9]*',
								'done'		=> '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
				),
			),
		),
    ),
);
