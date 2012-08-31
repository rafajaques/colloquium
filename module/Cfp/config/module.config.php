<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Cfp\Controller\Index' => 'Cfp\Controller\IndexController',
        ),
    ),
	'view_manager' => array(
		'template_path_stack' => array(
			'cfp' => __DIR__ . '/../view',
		),
	),
    'router' => array(
        'routes' => array(
            'cfp' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cfp[/]',
                    'defaults' => array(
                        'controller'    => 'Cfp\Controller\Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
					'ok' => array(
						'type' => 'Segment',
						'options' => array(
							'route' => 'submit/ok[/]',
							'defaults' => array(
								'controller'	=> 'Cfp\Controller\Index',
								'action'		=> 'ok',
							),
						),
					),
                    'inner' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[:action[/:id]][/]',
                            'constraints' => array(
                                'action'	=> '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'		=> '[0-9]*',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
