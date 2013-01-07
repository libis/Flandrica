<?php


// Add plugin hooks.
add_plugin_hook('install', 'themas_install');
add_plugin_hook('uninstall', 'themas_uninstall');
add_plugin_hook('define_routes', 'themas_define_routes');

function themas_install()
{

}

function themas_uninstall()
{

}

/**
 * Adds 2 routes for the form and the thank you page.
 **/
function themas_define_routes($router)
{
	$router->addRoute(
	    'themas_index',
	    new Zend_Controller_Router_Route(
	        THEMAS_PAGE_PATH,
	        array('module'       => 'themas')
	    )
	);

	/*$router->addRoute(
	    'simple_contact_form_thankyou',
	    new Zend_Controller_Router_Route(
	        SIMPLE_CONTACT_FORM_PAGE_PATH . 'thankyou',
	        array(
	            'module'       => 'simple-contact-form',
	            'controller'   => 'index',
	            'action'       => 'thankyou',
	        )
	    )
	);*/

}
