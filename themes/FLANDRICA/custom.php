<?php
/**
* Custom function to retrieve any number of random featured items.
*
* @param int $num The number of random featured items to return
* @param boolean $withImage Whether to return items with derivative images. True by default.
* @return array An array of Omeka Item objects.
*/
function Libis_get_featured_items($num = '10', $withImage = true)
{
   // Get the database.
    $db = get_db();

    $select = "
    SELECT i.* FROM {$db->prefix}items i
    INNER JOIN {$db->prefix}digitool_urls u on u.item_id = i.id
    WHERE i.public = '1' AND i.featured = '1'
    LIMIT 1";

    $items = $db->getTable("Item")->fetchObjects($select);

    return $items;
}

function libis_get_simple_page_content_by_id($id){
    $db = get_db();
    $page = $db->getTable('SimplePagesPage')->find($id);
    if($page):
        return $page->text;
    endif;
    return false;
}

//get current url
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

//get related exhibits by item
function Libis_link_to_related_exhibits($item) {

	$db = get_db();

	$select = "
	SELECT e.* FROM {$db->prefix}exhibits e
	INNER JOIN {$db->prefix}sections s ON s.exhibit_id = e.id
	INNER JOIN {$db->prefix}section_pages sp on sp.section_id = s.id
	INNER JOIN {$db->prefix}items_section_pages isp ON isp.page_id = sp.id
	WHERE isp.item_id = ?";

	$exhibits = $db->getTable("Exhibit")->fetchObjects($select,array($item->id));

	if(!empty($exhibits)) {
		foreach($exhibits as $exhibit) {
			$html .= exhibit_builder_link_to_exhibit($exhibit).', ';
		}
		//cut off last comma
		$html = substr($html,0,-2);
		return $html;
	}
	//if nothing was found
	return false;
}

//e-cards-form
function Libis_ecard_form(){
	//check GET for selected image pid
	$img = $_GET['afbeelding'];
	if($img == ''){
		return "<p>U heeft nog geen afbeelding geselecteerd, er kan dus geen e-card verzonden worden.</p>";
	}
	//get thumb from digitool
	$img = "http://libis-t-rosetta-1.libis.kuleuven.be/lias/cgi/get_pid?redirect&usagetype=THUMBNAIL&pid=".$img."&custom_att_3=stream";

	if(!isset($_POST['emailV'])):?>
	<div id="ecard">
		<form method="POST" name="email">
		<h5>Ontvanger</h5>
		<p><label for='name'>Name: </label>
		<input type="text" name="name" ></p>
		<p><label for='email'>E-mail: </label>
		<input type="text" name="email" ></p>
		<br>
		<h5>Verzender</h5>
		<p><label for='name'>Name: </label>
		<input type="text" name="nameV" ></p>
		<p><label for='email'>E-mail: </label>
		<input type="text" name="emailV" ></p>
		<br>
		<h5>Bericht</h5>
		<p><label for='subject'>Onderwerp: </label>
		<input type="text" name="onderwerp" ></p>
		<p><label for='message'>Message:</label>
		<textarea name="message"></textarea></p>

		<p>Volgende afbeelding zal verzonden worden:<br><br>
			<img src="<?php echo $img; ?>">
		</p>

		<input class="button" type="submit" value="Verzend" name='submit'>
		</form>
	</div>
	<?php
	endif;
	//VALIDATE AND SEND
	if(isset($_POST['emailV'])){

		$email_to = $_POST['email'];
		$email_subject =  $_POST['onderwerp'];


		function died($error) {
			// your error code can go here
			echo "<p>We are very sorry, but there were error(s) found with the form you submitted.</p>";

			echo $error."<br /><br />";
			echo "Please go back and fix these errors.<br /><br />";
			die();
		}

		// validation expected data exists
		if(!isset($_POST['name']) ||
		!isset($_POST['nameV']) ||
		!isset($_POST['email']) ||
		!isset($_POST['onderwerp'])){
			died('Gelieve het hele formulier in te vullen.');
		}

		$name = $_POST['name']; // required
		$nameV = $_POST['nameV']; // required
		$email_from = $_POST['emailV']; // required
		$email_to = $_POST['email']; // required
		$onderwerp = $_POST['onderwerp']; // not required
		$message = $_POST['message']; // required

		$message = $message."<br><br><a href='".$img."'><img src='".$img."'></a>";

		$error_message = "";
		$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
		if(!preg_match($email_exp,$email_from)) {
			die('Het e-mailadres van de verzender is niet correct.<br />');
		}
		if(!preg_match($email_exp,$email_to)) {
			die('Het e-mailadres van de ontvanger is niet correct.<br />');
		}
		$string_exp = "/^[A-Za-z .'-]+$/";
		if(!preg_match($string_exp,$name)) {
			die('De naam van de ontvanger is niet correct.<br />');
		}
		if(!preg_match($string_exp,nameV)) {
			die('De naam van de verzender is niet correct.<br />');
		}

		function clean_string($string) {
			$bad = array("content-type","bcc:","to:","cc:","href");
			return str_replace($bad,"",$string);
		}

		//SEND EMAIL
		$mail = new Zend_Mail();
		//handle attachement
		$mail->setType(Zend_Mime::MULTIPART_RELATED);
		$mail->setBodyHtml($message);
		$mail->setFrom($email_from,$nameV);
		$mail->addTo($email_to,$name);
		$mail->setSubject($onderwerp);
		/*
		$at = $mail->createAttachment($img);
		$at->type        = 'text/html';
		$at->disposition = Zend_Mime::DISPOSITION_INLINE;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;*/
		$mail->send();

		?>

	<!-- Made it this far so everything is ok -->

	<p>Je E-card werd met succes verzonden.</p>

	<?php

	}
}

//temp function to convert the pids table to digitool_url objects
function Libis_set_images(){
	set_time_limit(400);
	//get items with digitool urls in type afbeelding
	$elementId = get_db()->getTable('Element')->findByElementSetNameAndElementName('Item Type Metadata', 'PID2')->id;
	$params = array('advanced_search' =>
				array(
					array('element_id' => $elementId,
				'type' => 'is not empty'

				)
			)
		);
	$db = get_db();
	$items = get_items($params,12780);
	//return(sizeof($items));
	$i=0;
	foreach($items as $item){
		$i++;
		//check if item has digitool has digitool ids
		set_current_item($item);

		$bind = array(
				  'item_id'=>$item->id,
				  'pid'=> item('Item Type Metadata','PID2')
		);

		$db->insert('digitool_urls', $bind);
		//}



	}
	return $i;
}

function Libis_getNieuws($number,$lang = 'nl'){
    $type="Nieuwsbericht";
    
    if($lang == 'en'):
        $type="Nieuwsbericht-en";        
    endif;
    
	$items = get_items(array('type'=> $type,'recent'=>true),$number);
	//get current date
	$now= strtotime(date('Y-m-d'));

	set_items_for_loop($items);
	while(loop_items()):
	if($now >= strtotime(item('Item Type Metadata','Van')) && ($now <= strtotime(item('Item Type Metadata','Tot')) || item('Item Type Metadata','Tot')==null)){

		$html .= "<div class='blok-left'><h2>".link_to_item(item('Dublin Core','Title'))."</h2>";
		if(item_has_thumbnail()){
			$html .= "<div style='float:left;width:150px;'>".item_thumbnail(array("width"=>"140"))."</div>";
			$html .= "<div class='content'>";
		}else{
			$html .= "<div class='content' style='margin-left:10px;'>";
		}
		$html .= "<p>".item('Dublin Core','Description')."</p>";
		// Sam lees meer verwijderd
		//$html .= "<h3>".link_to_item("Lees meer",array("class"=>"more"))."</h3>";
		$html .= "</div></div>";
	}
	endwhile;

		/*$html .= "<div class='blok meer'>";
		$html .= "<p><a href='".uri('items/browse?type=Nieuwsbericht')."' class='more'><strong>meer activiteiten</strong></a></p>";
		$html .= "</div>";
		*/
	return $html;
}

function Libis_getRondleidingen($number){
	$html ="";
        $i=0;
        $lang =  libis_get_language();
        $exhibits=array();
        
        //get featured exhibit in right language
        $featured = exhibit_builder_get_exhibits(array('featured'=>'true','limit'=> 50));
        foreach($featured as $exhibit):
            if(substr( $exhibit->slug, 0, 2 ) === $lang || ($lang=='nl' && substr( $exhibit->slug, 0, 2 ) !== 'en')):
                $exhibits[]= $exhibit;
            endif;
        endforeach;
        
        //add extra non-featured exhibit if needed
        if(sizeof($exhibits) < $number):
            $exhibits_extra = exhibit_builder_get_exhibits(array('recent'=>'true','featured'=>false,'limit'=> 50));
            foreach($exhibits_extra as $exhibit):
                if(substr( $exhibit->slug, 0, 2 ) === $lang || ($lang=='nl' && substr( $exhibit->slug, 0, 2 ) !== 'en')):
                    $exhibits[]= $exhibit;
                endif;
            endforeach;            
        endif;
        
        $exhibits = array_slice($exhibits,0,$number);       
        
	foreach($exhibits as $exhibit):
            //check language
            $items = get_items(array('exhibit'=> $exhibit->id));
            $html .= "<div class='blok border-bottom'>";
            $html .= "<h2>".exhibit_builder_link_to_exhibit($exhibit,$exhibit->title)."</h2>";

            if($exhibit->thumbnail){
                $html .= '<div class="col">';
                $html .= '<img src="'.digitool_get_thumb_url_by_pid($exhibit->thumbnail).'">';
                $html .= '</div>';
            }else{
                foreach($items as $item){
                    //get ONE thumb
                    if(digitool_item_has_digitool_url($item)){
                        $html .= '<div class="col">';
                        $html .= digitool_get_thumb($item,true);
                        $html .= '</div>';
                        break;
                    }
                }
            }               

            $html .= "";
            $html .= "<p>".snippet_by_word_count($exhibit->description,40)."</p>";
            $html .= "<h3>" .exhibit_builder_link_to_exhibit($exhibit,__("To the tour"),array('class'=>'more')). "</h3>";
            $html .= "</div>";
                      
        endforeach;
        
	return $html;       
}

/**
 * Returns a breadcrumb for a given page / adjusted for Flandrica.
 *
 * @uses uri(), html_escape()
 * @param integer|null The id of the page.  If null, it uses the current simple page.
 * @param string $separator The string used to separate each section of the breadcrumb.
 * @param boolean $includePage Whether to include the title of the current page.
 **/
function Libis_display_breadcrumbs($pageId = null, $seperator=' > ', $includePage=true)
{
	$html = '';

	if ($pageId === null) {
		$page = get_current_simple_page();
	} else {
		$page = get_db()->getTable('SimplePagesPage')->find($pageId);
	}

	if ($page) {
		$ancestorPages = get_db()->getTable('SimplePagesPage')->findAncestorPages($page->id);
		$bPages = array_merge(array($page), $ancestorPages);

		// make sure all of the ancestors and the current page are published
		foreach($bPages as $bPage) {
			if (!$bPage->is_published) {
				$html = '';
				return $html;
			}
		}

		// find the page links
		$pageLinks = array();
		foreach($bPages as $bPage) {
			if ($bPage->id == $page->id) {
				if ($includePage) {
					$pageLinks[] = "<li>".html_escape($bPage->title)."</li>";
				}
			} else {
				$pageLinks[] = '<li><a href="' . uri($bPage->slug) .  '">' . html_escape($bPage->title) . '</a></li>';
			}
		}
		$pageLinks[] = '<ul><li><a href="'.uri('').'">' . __('Home') . '</a></li>';

		// create the bread crumb
		$html .= implode("",array_reverse($pageLinks));
		$html .= "</ul>";
	}
	return $html;
}

/**
 * Output a tag string given an Item, Exhibit, or a set of tags. -> AANGEPAST VOOR FLANDRICA
 *
 * @internal Any record that has the Taggable module can be passed to this function *
 * @param string|null $link The URL to use for links to the tags (if null, tags aren't linked) *
 * @return string HTML
 */
function Libis_tag_string($recordOrTags = null, $link=null)
{
	if (!$recordOrTags) {
		$recordOrTags = array();
	}

	if ($recordOrTags instanceof Omeka_Record) {
		$tags = $recordOrTags->Tags;
	} else {
		$tags = $recordOrTags;
	}

	$tagString = '';
	if (!empty($tags)) {
		$tagStrings = array();
		foreach ($tags as $key=>$tag) {
			if (!$link) {
				$tagStrings[$key] = html_escape($tag['name']);
			} else {
				$tagStrings[$key] = "<li><a href='" . html_escape($link.urlencode('"'.$tag['name'].'"')) . "' rel='tag'>".html_escape(__($tag['name']))."</a></li><br>";
			}
		}
		$tagString = join("",$tagStrings);
	}
	return $tagString;
}

/**
 * Output a tag string given an Item, Exhibit, or a set of tags. -> AANGEPAST VOOR FLANDRICA
 *
 * @internal Any record that has the Taggable module can be passed to this function *
 * @param string|null $link The URL to use for links to the tags (if null, tags aren't linked) *
 * @return string HTML
 */
function Libis_tag_string2($recordOrTags = null, $link=null)
{
	if (!$recordOrTags) {
		$recordOrTags = array();
	}

	if ($recordOrTags instanceof Omeka_Record) {
		$tags = $recordOrTags->Tags;
	} else {
		$tags = $recordOrTags;
	}

	$tagString = '';
	if (!empty($tags)) {
		$tagStrings = array();
		foreach ($tags as $key=>$tag){
			$tagStrings[$key] = html_escape(__($tag['name']));
		}
		$tagString = join("\",\"",$tagStrings);
	}
	return $tagString;
}

/**
 * Output a tag string given an Item, Exhibit, or a set of tags. -> AANGEPAST VOOR FLANDRICA
 *
 * @internal Any record that has the Taggable module can be passed to this function *
 * @param string|null $link The URL to use for links to the tags (if null, tags aren't linked) *
 * @return string HTML
 */
function Libis_tag_string3($recordOrTags = null, $link=null)
{
	if (!$recordOrTags) {
		$recordOrTags = array();
	}

	if ($recordOrTags instanceof Omeka_Record) {
		$tags = $recordOrTags->Tags;
	} else {
		$tags = $recordOrTags;
	}

	$tagString = '';
	if (!empty($tags)) {
		$tagStrings = array();
		foreach ($tags as $key=>$tag){
			$tagStrings[$key] = html_escape($tag['name']);
		}
		$tagString = join("\",\"",$tagStrings);
	}
	return $tagString;
}

/*
 * function returns an exhibit in wich a given item is used
 * @param: the item object
 * returns: exhibit info in html
 */
function Libis_link_to_related_exhibits_home($item) {
	require_once "Exhibit.php";
	$db = get_db();

         $select = "
            SELECT e.* FROM {$db->prefix}exhibits e
            INNER JOIN {$db->prefix}sections s ON s.exhibit_id = e.id
            INNER JOIN {$db->prefix}section_pages sp on sp.section_id = s.id
            INNER JOIN {$db->prefix}items_section_pages isp ON isp.page_id = sp.id
            WHERE isp.item_id = ?";
        
        if(libis_get_language()=='en'):        
            $select .= " AND locate('en-',e.slug)=1";
        else:
            $select .= " AND locate('en-',e.slug)!=1";
        endif;
        
	$exhibits = $db->getTable("Exhibit")->fetchObjects($select,$item->id);
        if(!empty($exhibits)){
            echo '<aside class="blok2 right background-gray">';
            echo '<div class="subtitle">'.__('featured in guided tour').'</div>';
        
            foreach($exhibits as $exhibit){                
                echo '<h2><a href="'.exhibit_builder_exhibit_uri($exhibit).'">'.$exhibit->title.'</a></h2>';
                //echo '<img src="images/dummy.jpg" class="left" />';
                echo '<a style="float:left;width:150px;" href="'.exhibit_builder_exhibit_uri($exhibit).'">' .Libis_get_first_image_exhibit($exhibit) .'</a>';
                echo '<p>'.snippet_by_word_count($exhibit->description,20).'</p>';
                echo '<p class="more" ><a href="'.exhibit_builder_exhibit_uri($exhibit).'">'.__('To the tour').'</a></p>';
            }    
            echo "</aside>";
        }else{
            return false;
        }
}


function Libis_get_first_image_exhibit($exhibit,$width=140,$type="",$fromFile=false){
	$itemcount=0;
	$page="";
	$section = $exhibit->getFirstSection();
	if(!empty($section)){
		$page= $exhibit->getFirstSection()->getPageByOrder(1);
		$itemcount = count($page['ExhibitPageEntry']);

		$itempageobject = $page['ExhibitPageEntry'];
		$found=false;
		if($itemcount>0){
			for ($i=1; $i <= $itemcount; $i++) {
				$item = $itempageobject[$i]['Item'];
				if(!empty($item)){
					if (digitool_item_has_digitool_url($item)):
						if($type=='view'){
							return digitool_get_view($item, true, false,$width);
						}
						return digitool_get_thumb($item, true, false,$width);
					endif;
				}
			}
		}
	}
}

/*
 * returns the data for the cycle script on the homepage
 * @params: 'tag' is used when you want items with a given tag
 * returns 10 items (with an image) in th format expected by jQuery
 */
function Libis_get_cycleData($tag = ""){
	$items = "";
	$items = Libis_get_items($tag);

	//construct the json string
	foreach($items as $item){
		//$tags = get_tags(array('record'=>$item), $limit = 10);
		set_current_item($item);
		$itemDescription = "";
		$itemDescription .= item('Dublin Core', 'Publisher') ? item('Dublin Core', 'Publisher')."<br>" : '';
		$itemDescription .= item('Item Type Metadata', 'Creatie plaats') ? item('Item Type Metadata', 'Creatie plaats')."<br>" : '';
		$itemDescription .= item('Item Type Metadata', 'Periode') ? item('Item Type Metadata', 'Periode') : '';


		$html .= '{';
		$html .= 'image:"'.digitool_get_thumb_url($item).'",';
		$html .= 'title:"'.item('Dublin Core','Title',array('snippet'=>'35')).'",';
		$html .= 'link:"'.item_uri().'",';
		$html .= 'author:"'.item('Dublin Core','Creator').'",';
		$html .= 'description:"'.$itemDescription.'",';
		$html .= 'textData:["'.Libis_tag_string2($item,uri('items/browse/')).'"],';
                $html .= 'nameData:["'.Libis_tag_string3($item,uri('items/browse/')).'"]';
		$html .= '},';
	}
	//remove last comma
	$html = substr_replace($html ,"",-1);
	return $html;
}

/*
 * gets items for the cycle plugin on the homepage
*/
function Libis_get_items($tag="",$num = '10', $withImage = true)
{
	// Get the database.
	$db = get_db();

	$select = "
	SELECT DISTINCT i.*,RAND() AS myRandom FROM {$db->prefix}items i
	INNER JOIN {$db->prefix}digitool_urls u on u.item_id = i.id";
	if($tag != ""){
		$select .=
		" INNER JOIN {$db->prefix}taggings t on t.relation_id = i.id INNER JOIN {$db->prefix}tags a on a.id = t.tag_id";
	}
	$select .= " WHERE i.public = '1' AND i.item_type_id = '15'";
	if($tag != ""){
		$select .= " AND a.name = '".$tag."'";
	}
	$select .= " ORDER BY myRandom DESC";
	$select .= " LIMIT 10";

	//echo "<br>".$select."<br>";

	$items = $db->getTable("Item")->fetchObjects($select);

	return $items;
}

/**
 * Returns html for a google map
 * @param string $divId The id of the div that holds the google map
 * @param array $options Possible options include:
 *     form = 'geolocation'  (provides the prefix for form elements that should
 *     catch the map coordinates)
 * @return array
 **/
function Libis_geolocation_google_map($divId = 'map', $options = array()) {

	$ht = '';
	$ht .= '<div id="' . $divId . '" class="map" style="width:100%; height:100%"></div>';

	// Load this junk in from the plugin config
	$center = geolocation_get_center();

	// The request parameters get put into the map options
	$params = array();
	if (!isset($options['params'])) {
		$params = array();
	}
	$params = array_merge($params, $_GET);

	if ($options['loadKml']) {
		unset($options['loadKml']);
		// This should not be a link to the public side b/c then all the URLs that
		// are generated inside the KML will also link to the public side.
		$options['uri'] = uri('geolocation/map.kml');
	}

	// Merge in extra parameters from the controller
	if (Zend_Registry::isRegistered('map_params')) {
		$params = array_merge($params, Zend_Registry::get('map_params'));
	}

	// We are using KML as the output format
	$options['params'] = $params;

	$options = js_escape($options);
	$center = js_escape($center);

	ob_start();
	?>
    <script type="text/javascript">
    //<![CDATA[
        var <?php echo Inflector::variablize($divId); ?>OmekaMapBrowse = new OmekaMapBrowse(<?php echo js_escape($divId); ?>, <?php echo $center; ?>, <?php echo $options; ?>);
    //]]>
    </script>
<?php
    $ht .= ob_get_contents();
    ob_end_clean();
    return $ht;
}

function Libis_page_exists($needle, $pages){
    foreach ($pages as $page) {
       if ($page['slug'] === $needle) {
           return true;
       }
    }
    return false;
}

function Libis_language_widget(){
    if(!$_SESSION['lang'] || $_SESSION['lang']=='nl'){
        $html = "<a href='".uri("/?lang=en")."'>EN</a>";
        $html .= " | NL<br>";
        return $html;
    }
     if($_SESSION['lang']=='en'){
        $html = "EN";
        $html .= " | <a href='".uri("/?lang=nl")."'>NL</a><br>";
        return $html;
    }   
}

function libis_get_language(){
    if(!isset($_SESSION['lang']) || $_SESSION['lang']=='nl'){
        return "nl";
    }else{
        return $_SESSION['lang'];
    }
}
?>