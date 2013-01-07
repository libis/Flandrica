<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 * @subpackage ExhibitBuilder
 **/

if (!defined('EXHIBIT_PLUGIN_DIR')) {
    define('EXHIBIT_PLUGIN_DIR', dirname(__FILE__));
    if (defined('WEB_PLUGIN')) {
        define('WEB_EXHIBIT_PLUGIN_DIR', WEB_PLUGIN . '/' 
            . basename(dirname(__FILE__)));
    }
    define('EXHIBIT_LAYOUTS_DIR_NAME', 'exhibit_layouts');
    define('EXHIBIT_LAYOUTS_DIR', EXHIBIT_PLUGIN_DIR 
        . '/views/shared/exhibit_layouts');
}

add_plugin_hook('install', 'exhibit_builder_install');
add_plugin_hook('uninstall', 'exhibit_builder_uninstall');
add_plugin_hook('upgrade', 'exhibit_builder_upgrade');
add_plugin_hook('define_acl', 'exhibit_builder_setup_acl');
add_plugin_hook('define_routes', 'exhibit_builder_routes');
add_plugin_hook('public_theme_header', 'exhibit_builder_public_header');
add_plugin_hook('admin_theme_header', 'exhibit_builder_admin_header');
add_plugin_hook('admin_append_to_dashboard_primary', 'exhibit_builder_dashboard');
add_plugin_hook('config_form', 'exhibit_builder_config_form');
add_plugin_hook('config', 'exhibit_builder_config');
add_plugin_hook('initialize', 'exhibit_builder_initialize');
add_plugin_hook('item_browse_sql', 'exhibit_builder_item_browse_sql');
add_plugin_hook('admin_append_to_advanced_search', 'exhibit_builder_append_to_advanced_search');
add_plugin_hook('public_append_to_advanced_search', 'exhibit_builder_append_to_advanced_search');

// This hook is defined in the HtmlPurifier plugin, meaning this will only work
// if that plugin is enabled.
add_plugin_hook('html_purifier_form_submission', 'exhibit_builder_purify_html');

add_filter('public_navigation_main', 'exhibit_builder_public_main_nav');
add_filter('admin_navigation_main', 'exhibit_builder_admin_nav');
add_filter('theme_options', 'exhibit_builder_theme_options');
add_filter('public_theme_name', 'exhibit_builder_public_theme_name');

// Helper functions for exhibits, exhibit sections, and exhibit pages
require_once EXHIBIT_PLUGIN_DIR . '/helpers/ExhibitFunctions.php';
require_once EXHIBIT_PLUGIN_DIR . '/helpers/ExhibitSectionFunctions.php';
require_once EXHIBIT_PLUGIN_DIR . '/helpers/ExhibitPageFunctions.php';

require_once EXHIBIT_PLUGIN_DIR . '/functions.php';


//ADDED by JORIS (source: https://gist.github.com/1007255)-->use by calling f.e. $items = get_items(array('exhibit' => '20')); // Replace 20 with your
//exhibit's ID, or the Exhibit object. 
// Hook the 'filter_items_by_exhibit' into the Items SQL.
add_plugin_hook('item_browse_sql', 'filter_items_by_exhibit');

/**
 * Filters the 'item_browse_sql' select to return items in a given
 * Exhibit. Checks for the existence of a parameter called 'exhibit'
 * with a value of either an Exhibit object or Exhibit ID.
 */
function filter_items_by_exhibit($select, $params)
{
	$db = get_db();

	if ($request = Zend_Controller_Front::getInstance()->getRequest()) {
		$exhibit = $request->get('exhibit') ? $request->get('exhibit') : null;
	}

	$exhibit = isset($params['exhibit']) ? $params['exhibit'] : $exhibit;

	if ($exhibit) {
		$select->joinInner(
		array('isp' => $db->ExhibitPageEntry),
            'isp.item_id = i.id',
		array()
		);

		$select->joinInner(
		array('sp' => $db->ExhibitPage),
            'sp.id = isp.page_id',
		array()
		);

		$select->joinInner(
		array('s' => $db->ExhibitSection),
            's.id = sp.section_id',
		array()
		);

		$select->joinInner(
		array('e' => $db->Exhibit),
            'e.id = s.exhibit_id',
		array()
		);

		if ($exhibit instanceof Exhibit) {
			$select->where('e.id = ?', $exhibit->id);
		} elseif (is_numeric($exhibit)) {
			$select->where('e.id = ?', $exhibit);
		}
	}

	return $select;
}
//end Joris
