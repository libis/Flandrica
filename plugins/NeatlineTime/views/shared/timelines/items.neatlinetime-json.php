<?php
/**
 * The shared neatlinetime-json browse view for Items
 */

$neatlineTimeEvents = array();
while (loop_items()) {
    $itemTitle = item('Dublin Core', 'Title',array('snippet' => 25));
    $itemLink = abs_item_uri();

    $itemDescription = item('Dublin Core', 'Creator') ? item('Dublin Core', 'Creator')."<br>" : '';

    $itemDescription .= item('Item Type Metadata', 'Creatie plaats') ? item('Item Type Metadata', 'Creatie plaats')."<br>" : '';

    //AANPASSING JORIS
    /* VOORLOPIG NIET NODIG, HERZIEN NA TEST
    $itemDates[] = item('Dublin Core', 'Date Available');
    $itemDates[] = item('Dublin Core', 'Date Created');
    $itemDates[] = item('Dublin Core', 'Date Accepted');
    $itemDates[] = item('Dublin Core', 'Date Copyrighted');
    $itemDates[] = item('Dublin Core', 'Date Submitted');
    $itemDates[] = item('Dublin Core', 'Date Issued');
    $itemDates[] = item('Dublin Core', 'Date Modified');
    $itemDates[] = item('Dublin Core', 'Date Valid');
    $itemDates[] = item('Item Type Metadata', 'Periode');
    */
    //EINDE

    if ($file = get_db()->getTable('File')->findWithImages(item('id'), 0)) {
        $fileUrl = file_display_uri($file, 'square_thumbnail');
    }

    //get dates
    $itemDates = item('Dublin Core', 'Date','all');

    //turn dates into events
    if (!empty($itemDates)) {
        foreach ($itemDates as $itemDate) {
        	$itemDescription .= $itemDate ."<br>";

            $neatlineTimeEvent = array();
            $itemDate = preg_replace("/[^0-9]/","", $itemDate);
            if(strlen($itemDate)==4){
            	$itemDate = "January 01 ".$itemDate." 00:00:00 GMT-0600";
            }
            else{
            	if(strlen($itemDate)==8){
            		$first_half = substr($itemDate,0,4);
            		$second_half = substr($itemDate,4);
            		$itemDate = "January 01 ".$first_half." 00:00:00 GMT-0600/January 01 ".$second_half." 00:00:00 GMT-0600";
            	}
            }
            $dateArray = explode('/', $itemDate);


            if ($dateArray[0]) {
                $neatlineTimeEvent['start'] = $dateArray[0];

                if (count($dateArray) == 2) {
                    $neatlineTimeEvent['end'] = $dateArray[1];
                }

                $neatlineTimeEvent['title'] = $itemTitle;
                $neatlineTimeEvent['link'] = $itemLink;
                $neatlineTimeEvent['classname'] = neatlinetime_item_class();

                //image - Joris
                if(digitool_item_has_digitool_url(get_current_item())){
                	$imgUrl = digitool_get_thumb_url(get_current_item());
                }

                $neatlineTimeEvent['image'] = $imgUrl;

                //icon - Joris
                switch(item('Item Type Metadata','Materiaal')){
                	case "oude druk":
                		$icon =  "http://flandrica.be.halotest.cc.kuleuven.be/themes/FLANDRICA/images/map_icons/oude_druk.png";
                		break;
                	case "handschrift":
                		$icon = "http://flandrica.be.halotest.cc.kuleuven.be/themes/FLANDRICA/images/map_icons/handschrift.png";
                		break;
                	case "periodiek":
                		$icon = "http://flandrica.be.halotest.cc.kuleuven.be/themes/FLANDRICA/images/map_icons/periodiek.png";
                		break;
                	case "grafisch en documentair materiaal":
                		$icon = "http://flandrica.be.halotest.cc.kuleuven.be/themes/FLANDRICA/images/map_icons/grafisch.png";
                		break;
                	case "moderne druk":
                		$icon = "http://flandrica.be.halotest.cc.kuleuven.be/themes/FLANDRICA/images/map_icons/moderne_druk.png";
                		break;
                	default:
                		$icon = "http://flandrica.be.halotest.cc.kuleuven.be/themes/FLANDRICA/images/map_icons/default.png";
                }

                $neatlineTimeEvent['icon'] = $icon;

                if ($fileUrl) {
                    $neatlineTimeEvent['image'] = $fileUrl;
                }

                $neatlineTimeEvent['description'] = $itemDescription;
                $neatlineTimeEvents[] = $neatlineTimeEvent;
            }
        }
    }

}

$neatlineTimeArray = array();
$neatlineTimeArray['date-time-format'] = "iso8601";
$neatlineTimeArray['events'] = $neatlineTimeEvents;

$neatlinetimeJson = json_encode($neatlineTimeArray);

echo $neatlinetimeJson;