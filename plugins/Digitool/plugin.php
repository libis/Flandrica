<?php
require_once 'DigitoolUrl.php';

// Add plugin hooks.
add_plugin_hook('install', 'digitool_install');
add_plugin_hook('uninstall', 'digitool_uninstall');
// add plugin hooks (configuration)
add_plugin_hook('config_form', 'digitool_config_form');
add_plugin_hook('config', 'digitool_config');
add_plugin_hook('admin_append_to_items_show_secondary', 'digitool_admin_show_item_map');

//add_plugin_hook('after_save_item', 'digitool_save_url');
add_plugin_hook('after_save_form_item', 'digitool_save_url');
add_filter('admin_items_form_tabs', 'digitool_item_form_tabs');

function digitool_install()
{
   $db = get_db();
    $sql = "
    CREATE TABLE IF NOT EXISTS $db->DigitoolUrl (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `item_id` BIGINT UNSIGNED NOT NULL ,
    `pid` VARCHAR(100) NOT NULL ,
    INDEX (`item_id`)) ENGINE = MYISAM";
    $db->query($sql);
    
    set_option('digitool_proxy','');
    set_option('digitool_cgi','');
    set_option('digitool_thumb','');
    set_option('digitool_view','');
    set_option('digitool_save','');
}

/**
 * Uninstall the plugin.
 */
function digitool_uninstall(){
    // Drop the url table.
    $db = get_db();
    $db->query("DROP TABLE $db->DigitoolUrl");
    
    delete_option('digitool_proxy');
    delete_option('digitool_cgi');
    delete_option('digitool_thumb');
    delete_option('digitool_view');
    delete_option('digitool_save');
}

//link to config_form.php
function digitool_config_form() {
	include('config_form.php');
}

//process the config_form
function digitool_config() {
	//get the POST variables from config_form and set them in the DB
	if($_POST["proxy"])
		set_option('digitool_proxy',$_POST['proxy']);

	if($_POST["cgi"])
		set_option('digitool_cgi',$_POST['cgi']);
        
        if($_POST["thumb"])
                set_option('digitool_thumb',$_POST['thumb']);
        
        if($_POST["view"])
                set_option('digitool_view',$_POST['view']);
        if($_POST["save"])
                set_option('digitool_save',$_POST['save']);
}

function digitool_admin_form($item){
	ob_start();
	echo js("jquery.pagination");
	?><link rel="stylesheet" href="<?php echo css('pagination'); ?>" /><?php
		//$html.="<form method='post' action=''>";
	if(digitool_item_has_digitool_url($item)){
	?>
	<div>
	<b>Digitool images currently associated with this item:</b><br>
	<br><?php echo digitool_get_thumb($item,false,true,'100px');?>
	<br><br><label>Remove current digitool images?</label><input type="checkbox" name="delete" value="yes"/>
	</div>
    <br><br>
    <?php }?>

    <label>Search digitool (case sensitive)</label>
	<br>
    <input name='fileUrl' id='fileUrl' type='text' class='fileinput' />
    <button style="float:none;" class="digi-search">Search</button>
    <br><br>
    <div id="wait" style="display:none;">Please wait, this might take a few seconds.</div>

    <div id="Pagination"></div>
    <br style="clear:both;" />
    <div id="Searchresult">
    This content will be replaced when pagination inits.
    </div>

    <!-- Container element for all the Elements that are to be paginated -->
    <div id="hiddenresult" style="display:none;">
     <div class="result">TEST</div>
    </div>


	<script>
	( function($) {
		jQuery('.digi-search').click(function(event) {
			event.preventDefault();
			jQuery('#Searchresult').hide('slow');
			jQuery('#Pagination').hide('slow');
			jQuery('#wait').show('slow');

			jQuery.get('<?php echo uri("digitool/index/cgi/");?>',{ search: jQuery('#fileUrl').val()} , function(data) {
				jQuery('#wait').hide('slow');
				jQuery('#hiddenresult').html(data);
				initPagination();
				pageselectCallback(0);
				jQuery('#Pagination').show('slow');
				jQuery('#Searchresult').show('slow');
			});

		});

		jQuery('.digi-child').live("click", function(event) {
			event.preventDefault();
			jQuery('#wait').show('slow');
			jQuery.get('<?php echo uri("digitool/index/childcgi/");?>',{ child: jQuery('.digi-child').val()} , function(data) {
				jQuery('#wait').hide('slow');
				jQuery('.result-child').html(data);
			});

		});

		// This demo shows how to paginate elements that were loaded via AJAX
		// It's very similar to the static demo.

		/**
		 * Callback function that displays the content.
		  *
		* Gets called every time the user clicks on a pagination link.
		*
		* @param {int}page_index New Page index
		* @param {jQuery} jq the container with the pagination links as a jQuery object
		*/
		function pageselectCallback(page_index, jq){
			var new_content = jQuery('#hiddenresult div.result:eq('+page_index+')').clone();
			jQuery('#Searchresult').empty().append(new_content);
		                return false;
		}

		/**
		* Callback function for the AJAX content loader.
		*/
		function initPagination() {
			var num_entries = jQuery('#hiddenresult div.result').length;
			// Create pagination element
			jQuery("#Pagination").pagination(num_entries, {
				num_edge_entries: 0,
				num_display_entries: 5,
				callback: pageselectCallback,
			                    items_per_page:4
			});
		}

	} ) ( jQuery );
		// Load HTML snippet with AJAX and insert it into the Hiddenresult element
		// When the HTML has loaded, call initPagination to paginate the elements

	</script>



	<?php
	$ht .= ob_get_contents();
	ob_end_clean();

	return $ht;

}

function digitool_save_url($item){

	//handle delete first
	if(isset($_POST['delete']) && ($_POST['delete'] == 'yes'))
	{
		$urlToDelete = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
		foreach($urlToDelete as $u){
			$u->delete();
		}

	}

	if(!$_POST['pid']){
		return;
	}

	$post = $_POST;

	//TODO:zie files-form.php voor code meerdere digitool files

	//create view url out of thumb url


	//save to db
	$url = new DigitoolUrl;
	$url->item_id = $item->id;

	$url->saveForm($post);
}



/**
* Add a Map tab to the edit item page
* @return array
**/
function digitool_item_form_tabs($tabs)
{
	// insert the map tab before the Miscellaneous tab
	$item = get_current_item();
	$ttabs = array();
	foreach($tabs as $key => $html) {
		if ($key == 'Tags') {
			$ht = '';
			$ht .= digitool_admin_form($item);
			$ttabs['Digitool'] = $ht;
		}
		$ttabs[$key] = $html;
	}
	$tabs = $ttabs;
	return $tabs;
}

/**
* Returns the html for loading the javascripts used by the plugin.
*
* @param bool $pageLoaded Whether or not the page is already loaded.
* If this function is used with AJAX, this parameter may need to be set to true.
* @return string
*/
function digitool_scripts()
{
	$ht = '';
	//$ht .= geolocation_load_google_maps();
	//$ht .= js('map');
	$ht .= js('jquery.pagination');
	//$ht .= css('pagination');
	return $ht;
}

/**
* Shows the digitool urls on the admin show page in the secondary column
* @param Item $item
* @return void
**/
function digitool_admin_show_item_map($item)
{
	$html = digitool_scripts()
	. '<div class="info-panel">'
	. '<h2>Digitool</h2>'
	. digitool_get_thumb($item,false,true,'100px')
	. '<br><br></div>';
	echo $html;
}


/**
* Shows an item's digitool url thumbnails
* @param Item $item, boolean $fiondOnlyOne, int $width,int $height
* @return html of the thumbnails
**/
function digitool_get_thumb($item, $findOnlyOne = false, $linkToView = false,$width="",$class="",$alt=""){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);
	$thumb_url = get_option('digitool_thumb');
        $view_url = get_option('digitool_view');

	if(!empty($url)){

		if(!$linkToView){
			if($findOnlyOne){
				$thumb = $thumb_url.$url->pid."&custom_att_3=stream";

				return "<img src='".$thumb."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'>";
			}
			//if more then one thumbnail was found
			else{
				$i=0;

				foreach($url as $u){

					$thumb = $thumb_url.$u->pid."&custom_att_3=stream";
					if($i==0)
						$html.="<img src='".$thumb."' class='first' width='".$width."' /> ";
					else
						$html.="<img src='".$thumb."' width='".$width."' /> ";

					$i++;
				}
				return $html;
			}
		}else{
			if($findOnlyOne){
				$thumb = $thumb_url.$url->pid."&custom_att_3=stream";
				$view = $view_url.$u->pid."&custom_att_3=stream";

				return "<a href='".$view."' target='_blank'><img src='".$thumb."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'></a>";
			}
			//if more then one thumbnail was found
			else{

				foreach($url as $u){

					$thumb = $thumb_url.$u->pid."&custom_att_3=stream";
					$view = $thumb_view.$u->pid."&custom_att_3=stream";

					$html.="<a href='".$view."' target='_blank'><img src='".$thumb."' width='".$width."'/></a> ";
				}
				return $html;
			}

		}
	}

}

function digitool_get_thumb_url($item){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, true);
        $thumb_url = get_option('digitool_thumb');
        
	//print_r($url);

	if(!empty($url)){
		$thumb = $thumb_url.$url->pid."&custom_att_3=stream";
		return $thumb;
	}else{
		return false;
	}

}

function digitool_get_thumb_url_by_pid($pid){
        $thumb_url = get_option('digitool_thumb');
	$thumb =  $thumb_url.$pid."&custom_att_3=stream";
	return $thumb;
}

function digitool_get_view_url($item){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, true);
       
        $view_url = get_option('digitool_view');
	
	if(!empty($url)){
		$view = $view_url.$url->pid."&custom_att_3=stream";
		return $view;
	}else{
		return false;
	}

}


/**
* Shows an item's digitool url views
* @param Item $item, boolean $findOnlyOne, int $width,int $height
* @return html of the views
**/
function digitool_get_view($item, $findOnlyOne = false, $linkToView = false,$width="",$class="",$alt=""){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);
	
        $view_url = get_option('digitool_view');

	if(!empty($url)){

		if(!$linkToView){
			if($findOnlyOne){				
				return "<img src='".digitool_get_image_from_file($url->pid)."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'>";
			}
			//if more then one thumbnail was found
			else{
				$i=0;

				foreach($url as $u){
					if($i==0)
						$html.= "<img src='".digitool_get_image_from_file($u->pid)."' width='".$width."' class='".$class." first' alt='".item("Dublin Core","Title",array(),$item)."'>";
					else
						$html.= "<img src='".digitool_get_image_from_file($u->pid)."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'>";
					$i++;
				}
				return $html;
			}
		}
	}

}

/**
 * Shows an item's digitool url views
 * @param Item $item, boolean $fiondOnlyOne, int $width,int $height
 * @return html of the views
 **/
function digitool_get_view_for_exhibit($item, $pid = null, $width="",$class="",$alt=""){

	if($pid){
		return "<img src='".digitool_get_image_from_file($pid)."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'>";
	}

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, true);
	//print_r($url);

	if(!empty($url)){
            return "<img src='".digitool_get_image_from_file($url->pid)."' width='".$width."' class='".$class."' alt='".item("Dublin Core","Title",array(),$item)."'>";
	}

	return false;

}

/**
* Checks if item has a digitool url
* @param Item $item
* @return true or false
**/
function digitool_item_has_digitool_url($item){

	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, $findOnlyOne);

	if(!empty($url)){
		return true;

	}
	else{
		return false;
	}

}

/**
* Returns a digitool thumbnail
* @param Item $item, boolean $fileFirst, int $size
* $fileFirst indicates which type of thumbnail will be returned, $size will set the width of the image
* @return true or false
**/
function digitool_thumbnail($item,$fileFirst = true, $size = "150",$class="",$alt=""){
	//show the thumbnail of a file object if present
	if($fileFirst && item_has_thumbnail($item)){
            //return the thumbnail (default size as in omeka settings)
            return item_square_thumbnail(array("class" => $class, "alt" => $alt, "width" => $size),"",$item);
	}
	//show the digitool url if there is one
	if(digitool_item_has_digitool_url($item))
            return digitool_get_thumb($item, true,false, $size," ", $class, $alt);

	//return false if there are no thumbnails
	return false;
}

function digitool_simple_gallery($item){
	$i=0;
	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
        $thumb_url = get_option('digitool_thumb');
        $view_url = get_option('digitool_view');
        
	if(sizeof($url)==1){
		$digi = $view_url.$url[0]->pid;
		$html.= "<center class='noprint'><a href='".$digi."'>Dit werk online raadplegen</a></center><br>";
		$html.= "<a class='noprint' href=".uri('ecards/?afbeelding='.$url[0]->pid).">Verstuur als e-card</a>";
		$view = $view_url.$url[0]->pid."&custom_att_3=stream";
		$html.="<div id='image'><img src='".$view."' width=250 height=100% /></div>";
		$html.= digitool_get_view(get_current_item(), false,250);
		return $html;
	}else{
		foreach($url as $u){
			$view = $view_url.$u->pid."&custom_att_3=stream";
			$thumb = $thumb_url.$u->pid."&custom_att_3=stream";
			$digi = $view_url.$u->pid;

			if($i==0){
				$html.= "<center class='noprint'><a href='".$digi."'>Dit werk online raadplegen</a></center><br>";
				$html.= "<br><a class='noprint' href=".uri('ecards/?afbeelding='.$u->pid).">Verstuur als e-card</a>";
				$html.="<div id='image'><img src='".$view."' width=250 height=100% /></div>";
			}
			$html.= "<a href='#' rel='".$view."' class='image'><img src='".$thumb."' class='thumb' width='50' height='50' border='0'/></a>";

			$i++;
		}
	}
	return $html;
}

//gallery for items/show in flandrica
function digitool_simple_gallery_flandrica($item,$link_rood="#"){
        
        $thumb_url = get_option('digitool_thumb');
        $view_url = get_option('digitool_view');
	$i=0;
	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
	if(sizeof($url)==1){
		$digi = $view_url.$url[0]->pid;
		$thumb = $thumb_url.$url[0]->pid."&custom_att_3=stream";
		
		$titel = digitool_get_metadata($url[0]->pid,'title');
		//html
		$html .= "<a href='".item('Item Type Metadata','Object instelling')."' target='_blank'><div class='bekijk-online'></div></a>
			<a rel='lightbox[pages]' title='".$titel."' href='".digitool_get_image_from_file($url[0]->pid)."'>";
		$html.= "<img src='".$thumb."' alt='".$titel."'/>";
		$html.= "<div class='tooltip'><div class='slideTitle'>".$titel."<span class='slideAuthor'></span></div><span class='slidePlace'></span></div>";
		$html.= "</a>";
		return $html;
	}else{
		foreach($url as $u){
			$thumb = $thumb_url.$u->pid."&custom_att_3=stream";
			$digi = $view_url.$u->pid;

			$titel = digitool_get_metadata($u->pid,'title');
			if($i==0){
				$html .= "<a href='".item('Item Type Metadata','Object instelling')."' target='_blank'><div class='bekijk-online'></div></a>";
			}
			// Sam: Toegevoegd, omdat het niet werkte
			$html .= "<a rel='lightbox[pages]' title='".$titel."' href='".digitool_get_image_from_file($u->pid)."'>";
			//$html.= "<a  class='lightLink' alt='".digitool_get_image_from_file($u->pid)."' title='".$titel."' href='#'>";

			$html.= "<img src='".$thumb."' alt='".$titel."' />";

			$html.= "<div class='tooltip' ><div class='slideTitle'>".$titel."<span class='slideAuthor'></span></div><span class='slidePlace'></span></div>";
			$html.= "</a>";
			$i++;                        
		}
	}
	return $html;
}

function digitool_get_metadata($pid,$text){
	$headers[] = 'Accept: text/html,text/xhtml+xml,text/xml';
	$headers[] = 'Connection: Keep-Alive';
	$headers[] = 'Content-type: text/xml;charset=UTF-8';
	$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0';


	$ch = curl_init("http://resolver.lias.be/get_metadata?pid=".$pid);

	//set options
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

	curl_setopt($ch, CURLOPT_HEADER, 0);

	curl_setopt($ch, CURLOPT_PROXY,get_option('digitool_proxy'));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

	//get data and close connection
	$data = curl_exec($ch);
	// Sam: status toegevoegd voor geen foutmeldingen 
	$status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	if($status ==200)
	{
		//die(curl_error($ch));
		curl_close($ch);
		$xml = simplexml_load_string($data);
		$entry = $xml->metadata->record;
		// aangepast door Sam om geen exception te krijgen
		if($entry != null){
		//Use that namespace
		$namespaces = $entry->getNameSpaces(true);
		//Now we don't have the URL hard-coded
		$dc = $entry->children($namespaces['dc']);
		return $dc->$text;
		} else {
			return "";
		}
	}
	//die(curl_error($ch));
	curl_close($ch);
	return "";

}
//returns a list of all thumbnails related to an item
function digitool_get_thumbnail_array($item){
        
        $thumb_url = get_option('digitool_thumb');
            
	$array = array();
	$url = get_db()->getTable('DigitoolUrl')->findDigitoolUrlByItem($item, false);
	if(sizeof($url)==1){

		$thumb = $thumb_url.$url[0]->pid."&custom_att_3=stream";
		$array [$url[0]->pid] = "<img src='".$thumb."' alt='".$titel."' width=140 />";

		return $array;

	}else{
		foreach($url as $u){
			$thumb = $thumb_url.$u->pid."&custom_att_3=stream";
			$array [$u->pid] = "<img src='".$thumb."' alt='".$titel."' width=140 />";
		}
	}
	return $array;
}


/**
 * Check if an image exists in the folder images/digitool and if not creates one using imageMagick
 * @param pid
 * @return image name
 **/
function digitool_get_image_from_file($pid){
        $settings = array('w'=>800,'scale'=>true);
        //also returns the file when already exists
	return "http://".$_SERVER['HTTP_HOST'].resize($pid,$settings);
}

/**
 * function by Wes Edling .. http://joedesigns.com
 * feel free to use this in any project, i just ask for a credit in the source code.
 * a link back to my site would be nice too.
 *
 *
 * Changes:
 * 2012/01/30 - David Goodwin - call escapeshellarg on parameters going into the shell
 * 2012/07/12 - Whizzkid - Added support for encoded image urls and images on ssl secured servers [https://]
 */

/**
 * SECURITY:
 * It's a bad idea to allow user supplied data to become the path for the image you wish to retrieve, as this allows them
 * to download nearly anything to your server. If you must do this, it's strongly advised that you put a .htaccess file
 * in the cache directory containing something like the following :
 * <code>php_flag engine off</code>
 * to at least stop arbitrary code execution. You can deal with any copyright infringement issues yourself :)
 */

/**
 * @param string $imagePath - either a local absolute/relative path, or a remote URL (e.g. http://...flickr.com/.../ ). See SECURITY note above.
 * @param array $opts (w(pixels), h(pixels), crop(boolean), scale(boolean), thumbnail(boolean), maxOnly(boolean), canvas-color(#abcabc), output-filename(string), cache_http_minutes(int))
 * @return new URL for resized image.
 */
function resize($pid,$opts=null){
        if($pid == "")
            return false;
    
        $view_url = get_option('digitool_view');
	$imagePath = urldecode($view_url.$pid."&custom_att_3=stream");
        
	# start configuration
	$cacheFolder = get_option('digitool_save'); # path to your cache folder, must be writeable by web server
        $remoteFolder = get_option('digitool_save'); # path to the folder you wish to download remote images into

	$defaults = array('crop' => false, 'scale' => 'false', 'thumbnail' => false, 'maxOnly' => false,
			'canvas-color' => 'transparent', 'output-filename' => false,
			'cacheFolder' => $cacheFolder, 'remoteFolder' => $remoteFolder, 'quality' => 90, 'cache_http_minutes' => 0);

	$opts = array_merge($defaults, $opts);

	$cacheFolder = $opts['cacheFolder'];
	$remoteFolder = $opts['remoteFolder'];

	$path_to_convert = 'convert'; # this could be something like /usr/bin/convert or /opt/local/share/bin/convert

	## you shouldn't need to configure anything else beyond this point

	$purl = parse_url($imagePath);
	$finfo = pathinfo($imagePath);
	$ext = "jpg";//$finfo['extension'];

	# check for remote image..
	if(isset($purl['scheme']) && ($purl['scheme'] == 'http' || $purl['scheme'] == 'https')):
	# grab the image, and cache it so we have something to work with..
	//list($filename) = explode('?',$finfo['basename']);
	$filename = $pid.".jpg";
	$local_filepath = $remoteFolder.$filename;
	$download_image = true;
	if(file_exists($remoteFolder.$pid."_w800.jpg")):           
            // Sam: if file exists toegevoegd anders een exception
            if(file_exists($local_filepath)):
                if(filemtime($local_filepath) < strtotime('+'.$opts['cache_http_minutes'].' minutes')):
                    //return filemtime($local_filepath).' - '.strtotime('+'.$opts['cache_http_minutes'].' minutes');
                    $download_image = false;
                endif;
                $download_image = false;
            endif;
            // Sam: toegevoegd anders werden de bestanden altijd gedownload
            $download_image = false;
	endif;
        
	if($download_image == true):            
            $vo_http_client = new Zend_Http_Client();
            $config = array(
                            'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
                            'proxy_host' => get_option('digitool_proxy'),
                            'proxy_port' => 8080
            );
            $vo_http_client->setConfig($config);
            $vo_http_client->setUri($imagePath);

            $vo_http_response = $vo_http_client->request();
            $thumb = $vo_http_response->getBody();
            //die($thumb);

            file_put_contents($local_filepath,$thumb);

	endif;
	$imagePath = $local_filepath;
	endif;

	if(file_exists($imagePath) == false):
            // Sam: toegevoegd anders moet het moeder bestand er altijd staan Er stond Document root + $imagepath
            $imagePath = $remoteFolder.$pid."_w800.jpg";
            if(file_exists($imagePath) == false):
                return 'image not found';
            endif;
	endif;

	if(isset($opts['w'])): $w = $opts['w']; endif;
	if(isset($opts['h'])): $h = $opts['h']; endif;

	$filename = $pid;

	// If the user has requested an explicit output-filename, do not use the cache directory.
	if(false !== $opts['output-filename']) :
            $newPath = $opts['output-filename'];
	else:
            if(!empty($w) and !empty($h)):
                $newPath = $cacheFolder.$filename.'_w'.$w.'_h'.$h.(isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "").(isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "").'.'.$ext;
            elseif(!empty($w)):
                $newPath = $cacheFolder.$filename.'_w'.$w.'.'.$ext;
            elseif(!empty($h)):
                $newPath = $cacheFolder.$filename.'_h'.$h.'.'.$ext;
            else:
                return false;
            endif;
	endif;

	$create = true;

	if(file_exists($newPath) == true):            
            $create = false;
            $origFileTime = date("YmdHis",filemtime($imagePath));
            $newFileTime = date("YmdHis",filemtime($newPath));
            if($newFileTime < $origFileTime): # Not using $opts['expire-time'] ??
                $create = true;
            endif;
	endif;

	if($create == true):
	if(!empty($w) and !empty($h)):

	list($width,$height) = getimagesize($imagePath);
	$resize = $w;

	if($width > $height):
            $resize = $w;
            if(true === $opts['crop']):
                $resize = "x".$h;
            endif;
        else:
            $resize = "x".$h;
            if(true === $opts['crop']):
                $resize = $w;
            endif;
	endif;

	if(true === $opts['scale']):
	$cmd = $path_to_convert ." ". escapeshellarg($imagePath) ." -resize ". escapeshellarg($resize) .
	" -quality ". escapeshellarg($opts['quality']) . " " . escapeshellarg($newPath);
	else:
	$cmd = $path_to_convert." ". escapeshellarg($imagePath) ." -resize ". escapeshellarg($resize) .
	" -size ". escapeshellarg($w ."x". $h) .
	" xc:". escapeshellarg($opts['canvas-color']) .
	" +swap -gravity center -composite -quality ". escapeshellarg($opts['quality'])." ".escapeshellarg($newPath);
	endif;

	else:
	$cmd = $path_to_convert." " . escapeshellarg($imagePath) .
	" -thumbnail ". (!empty($h) ? 'x':'') . $w ."".
	(isset($opts['maxOnly']) && $opts['maxOnly'] == true ? "\>" : "") .
	" -quality ". escapeshellarg($opts['quality']) ." ". escapeshellarg($newPath);
	endif;

	$c = exec($cmd, $output, $return_code);
	if($return_code != 0) {
		error_log("Tried to execute : $cmd, return code: $return_code, output: " . print_r($output, true));
		return false;
	}
	endif;

	# return cache file path
	return str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);
}

// Calculates restricted dimensions with a maximum of $goal_width by $goal_height 
function digitool_resize_dimensions($goal_width,$goal_height,$width,$height) { 
    $return = array('width' => $width, 'height' => $height); 

    // If the ratio > goal ratio and the width > goal width resize down to goal width 
    if ($width/$height > $goal_width/$goal_height && $width > $goal_width) { 
        $return['width'] = $goal_width; 
        $return['height'] = $goal_width/$width * $height; 
    } 
    // Otherwise, if the height > goal, resize down to goal height 
    else if ($height > $goal_height) { 
        $return['width'] = $goal_height/$height * $width; 
        $return['height'] = $goal_height; 
    } 

    return $return; 
}
?>