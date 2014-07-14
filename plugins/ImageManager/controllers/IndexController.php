<?php
require PLUGIN_DIR.'/ImageManager/views/helpers/ImageManagerFunctions.php';
/**
 * Image Manager
 *
 * @copyright Libis (libis.be)
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Image Manager index controller class.
 *
 * @package Image Manager
 */
class ImageManager_IndexController extends Omeka_Controller_Action
{    
   
    public function indexAction()
    {
      
    }

    public function connectorAction()
    {
        error_reporting(1);
        include_once PLUGIN_DIR.'/ImageManager/models/elFinderConnector.class.php';
        include_once PLUGIN_DIR.'/ImageManager/models/elFinder.class.php';
        include_once PLUGIN_DIR.'/ImageManager/models/elFinderVolumeDriver.class.php';
        include_once PLUGIN_DIR.'/ImageManager/models/elFinderVolumeFTP.class.php';
        include_once PLUGIN_DIR.'/ImageManager/models/elFinderVolumeLocalFileSystem.class.php';

        $x=class_exists('');

        $opts = ImageManager_config();
                
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }
      
}
