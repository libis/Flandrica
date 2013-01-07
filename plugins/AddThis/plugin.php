<?php
/* OMEKA PLUGIN ADDTHIS: 
 * This plug-in will add the addthis button to Omeka,
 * You will be able to configure where to show it (items or collections)
 * The button script can also be adjusted in the configuration menu
 */

// add plugin hooks (configuration)
add_plugin_hook('config_form', 'addThis_config_form');
add_plugin_hook('config', 'addThis_config');

//install and uninstall
add_plugin_hook('install', 'addThis_install');
add_plugin_hook('uninstall', 'addThis_uninstall');

//set the location
add_plugin_hook(get_option('addThis_hook'),'addThis_add');

//link to config_form.php
function addThis_config_form() {
	include('config_form.php');
}

//process the config_form
function addThis_config() {
	//get the POST variables from config_form and set them in the DB
	$layout = $_POST['addThis_layout'];
	set_option('addThis_layout','layout');
	
	//set the hook and checked/unchecked
	if($layout=='item'){
		set_option('addThis_hook','public_append_to_items_show');
		set_option('addThis_item_checked','checked');
		set_option('addThis_collection_checked','unchecked');
	}
	
	if($layout=='collection'){
		set_option('addThis_hook','public_append_to_collections_show');		
		set_option('addThis_collection_checked','checked');
		set_option('addThis_item_checked','unchecked');
	}
	
	//set script
	set_option('addThis_script',$_POST['addThis_script']);
}

//handle the installation
function addThis_install() {
	//set the default plugin options (items is set as default)
   	set_option('addThis_layout','item');
	set_option('addThis_item_checked','checked');
	set_option('addThis_collection_checked','unchecked');
	set_option('addThis_hook','public_append_to_items_show');		
	set_option('addThis_script','default');
}

//handle the uninstallation
function addThis_uninstall() {
    // Delete the plugin options
    delete_option('addThis_layout');
    delete_option('addThis_item_checked');
	delete_option('addThis_collection_checked');
	delete_option('addThis_hook');
	delete_option('addThis_script');				
}

//the plug-in's output
function addThis_add() {
	
	echo (get_option('addThis_script'));
	echo "<br>"; 
}