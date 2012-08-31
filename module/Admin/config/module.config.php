<?php

return array(
    'router' => array(
        'routes' => array(
			/* Admin stuff */
			'admin' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/admin[/]',
					'defaults' => array(
						'controller' => 'Admin\Controller\Index',
						'action' => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'conferences' => array(
						'type' => 'segment',
						'options' => array(
							'route' => 'conferences[/:action[/:id]][/]',
							'constraints' => array(
		                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
		                        'id'     => '[0-9]+',
		                    ),
							'defaults' => array(
								'controller' => 'Admin\Controller\Conferences',
							),
						),
					),
				),
			),
		),
    ),
    'controllers' => array(
        'invokables' => array(
			'Admin\Controller\Index' => 'Admin\Controller\IndexController',
			'Admin\Controller\Conferences' => 'Admin\Controller\ConferencesController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
);
