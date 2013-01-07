<?php
/* OMEKA PLUGIN RelateedTagCloud:
 * Adds a Tag cloud made up of related tags to the browse page,
 *
 */
//set the location
add_plugin_hook('public_append_to_items_browse','relatedTagCloud_add');

function relatedTagCloud_get()
{
	$itemsBrowse = get_items_for_loop();
	$hasTags = false;
	//check the tags of every item on the browsepage
	foreach ($itemsBrowse as $item){
			//don't go further if it doens't have tags
        	if(item_has_tags($item)){

        		// Sam: Proberen om geheugen te besparen
        		release_object($tagsItem);

        		//get the item's tags
        		$tagsItem = get_tags(array('record'=>$item));
        		$hasTags = true;
        		//saves all current tags so we can remove them from the cloud later
        		$doubleTags = array_merge($tagsItem,(array)$doubleTags);
        		//for each tag get items with the same tags
        		foreach($tagsItem as $key=>$item_tag){
        			// Sam: Proberen om geheugen te besparen
        			release_object($items);

        			// Sam: aan item_tag [name] toegevoegd om de array kleiner te maken
        			$items = get_items(array('tags'=>$item_tag['name']));
	        		foreach($items as $item){
	   						$tagsNew = get_tags(array('record'=>$item));
	   						$tags = array_merge($tagsNew,(array)$tags);
	        		}
			}
			}
			// Sam: Proberen om geheugen te besparen
			release_object($tagsItem);
			release_object($tagsNew);
			release_object($items);
	}
	release_object($itemsBrowse);
	if(!$hasTags){
		return " ";
	}else{
		$tags = array_unique($tags);
		$tags = array_diff($tags, $doubleTags);

		// Sam: Proberen om geheugen te besparen
		release_object($doubleTags);

		if(empty($tags))
			return " ";
		$tags = tag_cloud($tags, uri('items/browse/'));
		return $tags;
	}
}

function relatedTagCloud_show_get()
{
	$item = get_current_item();
	$hasTags = false;
	//check the tags of every item on the browsepage

	//don't go further if it doens't have tags
	if(item_has_tags($item)){
		//get the item's tags
		$tagsItem = get_tags($item);
		$hasTags = true;
		//saves all current tags so we can remove them from the cloud later
		$doubleTags = array_merge($tagsItem,(array)$doubleTags);
		//for each tag get items with the same tags
		foreach($tagsItem as $key=>$item_tag){
			// Sam: Proberen om geheugen te besparen
			release_object($items);

			// Sam: aan item_tag [name] toegevoegd om de array kleiner te maken
			$items = get_items(array('tags'=>$item_tag['name']));
			foreach($items as $item){
				$tagsNew = get_tags(array('record'=>$item));
				$tags = array_merge($tagsNew,(array)$tags);
			};

		}
	}

	if(!$hasTags){
		return " ";
	}else{
		$tags = array_unique($tags);
		$tags = array_diff($tags, $doubleTags);

		if(empty($tags))
			return " ";
		$tags = tag_cloud($tags, uri('items/browse/'));
			return $tags;
	}
}

//the plug-in's output
function relatedTagCloud_add() {

	$tags=relatedTagCloud_get();
	echo "<p>";
	echo $tags;
	echo "</p>";
}
?>