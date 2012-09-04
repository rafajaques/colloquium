<?php

return array(
    'bjyauthorize' => array(

		// set the 'guest' role as default
		'default_role' => 'guest',

		// Where should I search for the user id?
		'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',

		// Where are the damn roles?
		'role_providers' => array(
			'BjyAuthorize\Provider\Role\ZendDb' => array(
				'table'             => 'user_role',
				'role_id_field'     => 'role_id',
				'parent_role_field' => 'parent',
			),
		),

		// Resources
		'resource_providers' => array(
			'BjyAuthorize\Provider\Resource\Config' => array(
				'admin'	=> array(),
				'staff'	=> array(),
				'user'	=> array(),
			),
		),

		// Permissions down here :)
		'rule_providers' => array(
			'BjyAuthorize\Provider\Rule\Config' => array(
				'allow' => array(
					// roles (string or array), resource[, privilege]
					array('admin', 'admin'),
					array('staff', 'staff'),
					array('user', 'user'),
				),
			),
		),

		// Controller Permissions
		'guards' => array(
			'BjyAuthorize\Guard\Controller' => array(
				
				/**
				 * Guests
				 */
				// Home
				array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user')),
				
				// "My" pages
				array('controller' => 'Application\Controller\MySubmissions', 'roles' => array('user')),
				
				// Login
				array('controller' => 'zfcuser', 'roles' => array(/* Everybody! \o/ */)),
				
				// Conferences
				array('controller' => 'Conferences\Controller\Index', 'roles' => array(/* Everybody! \o/ */)),
				
				// Call for Papers
				array('controller' => 'Cfp\Controller\Index', 'action' => 'index', 'roles' => array(/* Everybody! \o/ */)),
				array('controller' => 'Cfp\Controller\Index', 'roles' => array('user')),
						
				/**
				 * Users
				 */
				
				/**
				 * Staff
				 */
				
				/**
				 * Admins
				 */
				array('controller' => 'Admin\Controller\Index', 'roles' => array('admin')),
				array('controller' => 'Admin\Controller\Conferences', 'roles' => array('admin')),
				
				/*array('controller' => 'index', 'action' => 'stuff', 'roles' => array('user')),
				array('controller' => 'zfcuser', 'roles' => array())*/
			),

			/*'BjyAuthorize\Guard\Route' => array(
				array('route' => 'zfcuser', 'roles' => array('user')),
				array('route' => 'zfcuser/logout', 'roles' => array('user')),
				array('route' => 'zfcuser/login', 'roles' => array('guest')),
				array('route' => 'zfcuser/register', 'roles' => array('guest')),
			),*/
		),
	),
);