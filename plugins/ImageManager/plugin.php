<?php
/**
 * Image Manager
 *
 * @copyright Libis (libis.be)
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */
$_hooks = array('initialize',
    'define_routes', 'config_form', 'config','admin_head');

add_plugin_hook('install','hookInstall');
add_plugin_hook('uninstall','hookUninstall');
add_plugin_hook('initialize','hookUninstall');
add_plugin_hook('define_routes','hookDefineRoutes');
add_plugin_hook('config','hookConfig');
add_plugin_hook('config_form','hookConfigForm');
add_plugin_hook('admin_theme_header','hookAdminHead');

add_filter('admin_navigation_main','filterAdminNavigationMain');

/**
 * @var array Filters for the plugin.
 */
$_filters = array('admin_navigation_main');

 /**
 * Install the plugin.
 */
function hookInstall()
{

}

/**
 * Uninstall the plugin.
 */
function hookUninstall()
{        

}


/**
 * Add the translations.
 */
function hookInitialize()
{
    add_translation_source(dirname(__FILE__) . '/languages');
    get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers', 'ImageManager_View_Helper_');
}

 /**
 * Add the routes for accessing simple pages by slug.
 * 
 * @param Zend_Controller_Router_Rewrite $router
 */
function hookDefineRoutes($router)
{
    // Add custom routes based on the page slug.
    $router->addRoute(
        'image-manager', 
        new Zend_Controller_Router_Route(
           "image-manager/", 
            array('module' => 'image-manager')
        )
    );

    $router->addRoute(
        'image-manager/connector', 
        new Zend_Controller_Router_Route(
           "image-manager/connector", 
            array('module' => 'image-manager',
                  'controller' => 'index',
                  'action' => 'connector'
                )
        )
    );
}

function hookAdminHead(){        
    //css
    queue_css('jquery_ui');
    queue_css('theme');
    queue_css('elfinder.min');
    //js
    //queue_js_url("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.js");
    queue_js('elFinder/js/elFinder.min');        
}

/**
 * Display the plugin config form.
 */
function hookConfigForm()
{
    require dirname(__FILE__) . '/config_form.php';
}

/**
 * Set the options from the config form input.
 */
function hookConfig()
{
    set_option('simple_pages_filter_page_content', (int)(boolean)$_POST['simple_pages_filter_page_content']);
}


/**
 * Add the Image Manager link to the admin main navigation.
 * 
 * @param array Navigation array.
 * @return array Filtered navigation array.
 */
function filterAdminNavigationMain($nav)
{
    $nav[__('Image Manager')] = uri('image-manager');
    
    return $nav;
}
?>