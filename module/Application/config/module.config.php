<?php

return array(
    'router' => array(
        'routes' => array(
			/* Index */
            'home' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			/* "My" Stuff */
			'my-conferences' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/my-conferences',
                    'defaults' => array(
                        'controller' => 'Application\Controller\MyConferences',
                        'action'     => 'index',
                    ),
                ),
            ),
            'my-submissions' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/my-submissions',
                    'defaults' => array(
                        'controller' => 'Application\Controller\MySubmissions',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
			'Application\Controller\Index' => 'Application\Controller\IndexController',
			'Application\Controller\MySubmissions' => 'Application\Controller\MySubmissionsController',
			'Application\Controller\MyConferences' => 'Application\Controller\MyConferencesController',
        ),
    ),
	'strategies' => array(
		'ViewJsonStrategy',
	),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
			'application' => __DIR__ . '/../view',
        ),
    ),
);