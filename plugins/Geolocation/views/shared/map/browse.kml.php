<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<kml xmlns="http://earth.google.com/kml/2.2">
    <Document>
        <name>Omeka Items KML</name>
        <?php /* Here is the styling for the balloon that appears on the map */ ?>
        <Style id="item-info-balloon">
            <BalloonStyle>
                <text><![CDATA[
                    <div class="mapsInfoWindow img">

                        $[description]

                    </div>
                ]]></text>
            </BalloonStyle>
        </Style>
 <?php
        //Zend_Session::start();
        $session = new Zend_Session_Namespace('style');
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if ($session->from == 'solr' || $session->from == 'show') {
            $locationSolr = array();
            if($session->items){
                $locations = $session->locations;
                foreach($session->items as $id){
                    $item = get_item_by_id($id);
                    $items[] = $item;
                    $locs = geolocation_get_location_for_item($item);
                    if(!empty($locs)){
                        if(sizeof($locs)>1){
                            foreach($locs as $loc){
                                $locationsSolr = $locationSolr + $loc;
                            }
                        }else{
                             $locationsSolr = $locationSolr + $locs;
                        }
                    }
                }

                $locations = $locationsSolr;
                echo(sizeof($session->items));
                set_items_for_loop($items);
            }
        }

        while(loop_items()):
        $item = get_current_item();
        $locationR = $locations[$item->id];
        foreach($locationR as $location){
        ?>
        <Placemark>

            <description><![CDATA[<?php
	          echo '<div class="item_id" style="display:hidden">'.$item->id.'</div>';
	            //echo "<div class='bookYear'>".item('Item Type Metadata', 'Periode')."</div>";
	            ?>
            ]]></description>
            <Icon>
            	<href>
		        <?php switch(item('Item Type Metadata','Materiaal')){
		        		case "oude druk":
		        			echo img('map_icons/oude_druk.png');
		        			break;
		        		case "handschrift":
		        			echo img('map_icons/handschrift.png');
		        			break;
	        			case "periodiek":
	        				echo img('map_icons/periodiek.png');
	        				break;
        				case "grafisch en documentair materiaal":
        					echo img('map_icons/grafisch.png');
        					break;
        				case "moderne druk":
        					echo img('map_icons/moderne_druk.png');
        					break;
		        		default:
		        			echo img('map_icons/default.png');
		        	}?>
		     	</href>
		     </Icon>
            <Point>
                <coordinates><?php echo $location['longitude']; ?>,<?php echo $location['latitude']; ?></coordinates>
            </Point>
            <?php if ($location['address']): ?>
            <address><![CDATA[<?php echo $location['address']; ?>]]></address>
            <?php endif; ?>
        </Placemark>
        <?php } endwhile; ?>
    </Document>
</kml>
