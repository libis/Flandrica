<?php head(array('title' => 'Beeldbank Kaart','bodyid'=>'map','bodyclass' => 'maps')); ?>
 <div class="clearfix"></div>
    </div>
<div id="style_one">
 	<div id="wrapper" class="cf">
		<div class="wrapper" >
        	<div id="map_container">

        		<?php echo Libis_geolocation_google_map('map_canvas', array('loadKml'=>true, 'list'=>'map_canvas'));?>

        	</div>
        </div>
    </div>


</div>

<?php foot(); ?>