<?php head(array('bodyid'=>'home')); ?>

<div class="cycle" id="cycle">
        <ul class="rotator"></ul>
        <a href="#" class="prev">Prev</a>
        <a href="#" class="next">Next</a>
        <div class="description"></div>
        <div class="thumbnail"></div>
</div>
<div id="style_one">
    <div id="wrapper" class="cf">
    	<div id="sidebar-left">
    		<div class="rubriek-l">Info</div>
    		<?php echo Libis_getNieuws(1);?>

		</div>
    	<div id="container">
            <div id="content">
                <div id="right">
                  <div class="rubriek">Nieuws</div>
                	<?php echo feedCollector_show();?>
                </div>
                <div id="main">
                  <div class="rubriek">Rondleidingen</div>
                	<?php echo Libis_getRondleidingen(3);?>
                </div>
            </div>
        </div>

        <div class="clearfix">&nbsp;</div>
    </div>
	</div>
	<div id="data">
		<span style="display:none;" id="getData" class="result">
			<?php $cycleData = Libis_get_cycleData($_POST['tag']);?>
			<?php echo $cycleData;?>
		</span>
	</div>
	<div id='test'></div>
</div>
<script>
	jQuery(document).ready(function($) {
		//get items for the first slideshow
		var cycleData2 = [<?php echo Libis_get_cycleData($_POST['tag']);?>];

		//init the slideshow when page loads
		if(jQuery.fn.roundabout){
			RoundAbout.init(jQuery(".cycle"), cycleData2);
		}

		//put a click event on al the tag/links
		jQuery(".cycle-link").live("click", function(e){
			//prevent the default event (the actual linking)
			e.preventDefault();
			var tagLink = jQuery(this).text();
			//remove the current slideshow
			jQuery('.cycle').html('<ul class="rotator"></ul><a href="#" class="prev">Prev</a><a href="#" class="next">Next</a><div class="description"></div><div class="thumbnail"></div>');
			//get new items using the code in getData div
			//alert(jQuery(this).text());
			jQuery('#test').load('/ #getData',{tag: tagLink},function(data){
				var result = '['+jQuery("#test .result").html()+']';
				//alert(jQuery("#test .result").html());
				//change string into array
				var myObject = eval('(' + result + ')');
				//initiate the slideshow again with the new data
				RoundAbout.init(jQuery(".cycle"),myObject);
			});
		});

		jQuery('.imagecache-linked').removeAttr('href');

	});
</script>

<?php foot(); ?>