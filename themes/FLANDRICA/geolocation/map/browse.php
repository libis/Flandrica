<?php head(array('title' => 'Beeldbank Kaart','bodyid'=>'map','bodyclass' => 'maps')); ?>
 <div class="clearfix"></div>
    </div>
<div id="style_one">
    <div id="wrapper" class="cf">
        <div class="wrapper" >
            <div id="map_container">

        		<?php echo Libis_geolocation_google_map('map_canvas', array('loadKml'=>true));?>

        	</div>
        </div>
    </div>


</div>
<div class="mapsInfoWindow" style="display:hidden">
    <div class="infoWindow">
    <?php 
    if($_POST['id']):
        
        set_current_item(get_item_by_id($_POST['id']));
        $item = get_current_item();
        if(digitool_item_has_digitool_url($item)){
            echo link_to_item(digitool_get_thumb($item, true, false,100,"bookImg"));
        }
        echo "<h2 class='bookTitle'>".link_to_item(item('Dublin Core', 'Title',array('snippet' => 25)))."</h2>";
        $itemDescription = item('Dublin Core', 'Creator') ? item('Dublin Core', 'Creator')."<br>" : '';
        $itemDescription .= item('Dublin Core', 'Publisher') ? item('Dublin Core', 'Publisher')."<br>" : '';

        $itemDescription .= item('Item Type Metadata', 'Creatie plaats') ? item('Item Type Metadata', 'Creatie plaats')."<br>" : '';

        $itemDescription .= item('Item Type Metadata', 'Periode') ? item('Item Type Metadata', 'Periode') : '';

        echo "<div class='bookAuthor'>".$itemDescription."</div>";
    endif;
    ?>
    </div>
    
</div>    
<?php foot(); ?>