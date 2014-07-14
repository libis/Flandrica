<?php
/**
* LICENSE: No License yet
*
* @package Image Manager
* @author Libis
*
*/
function ImageManager_config(){
    $opts = array(
        'locale' => '',
        'roots' => array (
            array(
                'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                'path' => PUBLIC_THEME_DIR.'/'.get_option('public_theme').'/images/', // path to files (REQUIRED)
                'URL' => WEB_PUBLIC_THEME.'/'.get_option('public_theme').'/images/', // URL to files (REQUIRED)
                'debug'=>true,
                'defaults' => array('read' => true, 'write' => true)               
            )
        )
    );

    return $opts;
}

function ImageManager_getLanguage(){
    if(isset($_SESSION['lang_po'])){
        return $_SESSION['lang_po'];
    }else{
        $config = new Omeka_Application_Resource_Locale();
        var_dump($config);
        //$config = $bootstrap->getResource('Config');
      
    }
}