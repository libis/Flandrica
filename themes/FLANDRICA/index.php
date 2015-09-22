<?php head(array('bodyid'=>'home','bodyclass'=>'home')); ?>

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
    		<div class="rubriek-l"><?php echo __('Info');?></div>
                
    		<?php echo Libis_getNieuws(1,libis_get_language());?>

		</div>
    	<div id="container">
            <div id="content">
                <?php if(libis_get_language()=='nl'):?>
                <div id="right">
                  <div class="rubriek"><?php echo __('News');?></div>
                	<?php echo feedCollector_show();?>
                </div>
              
                <?php endif;?>
                <?php if(libis_get_language()=='en'):?>
                <div id="right">                  
                    <div class="rubriek"><?php echo __('Explore');?></div>
                	<div id="explore"></div>                       
                        <script>jQuery("#explore").load('<?php echo WEB_ROOT.'/';?>explore_frame#themas_overview_mini');</script>
                    </div>                              
                <?php endif;?>
                <div id="main">
                  <div class="rubriek"><?php echo __('Tours');?></div>
                	<?php echo Libis_getRondleidingen(4);?>
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
			var tagLink = jQuery(this).attr('name');
			//remove the current slideshow
			jQuery('.cycle').html('<ul class="rotator"></ul><a href="#" class="prev">Prev</a><a href="#" class="next">Next</a><div class="description"></div><div class="thumbnail"></div>');
			//get new items using the code in getData div
			//alert(jQuery(this).text());
			jQuery('#test').load('<?php echo WEB_ROOT;?>/ #getData',{tag: tagLink},function(data){
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

<?php 
foot(); ?>