<?php
$title = __('Browse Exhibits');
head(array('title'=>$title, 'bodyid' => 'exhibit', 'bodyclass'=>'browse'));
?>
    <div class="clearfix"></div>
    <div id="breadcrumb">
    	<ul>
        	<li>
            	<a href="<?php echo html_escape(uri('')); ?>">Home</a>
            </li>
            <li>Rondleidingen</li>
		</ul>
    </div>
    <div id="style_three">
    <div id="wrapper" class="cf">

    	<div id="container">
            <div id="content">
                <div id="main" class="zebra">
                <div class="blok odd">
                	<h1>Rondleidingen</h1>
                </div>
                <?php $exhibitCount = 0; ?>
			    <?php while(loop_exhibits()): ?>
			    	<?php $exhibitCount++; ?>
			    	   	<div class="blok <?php if ($exhibitCount%2==1) echo ' evenRondleiding'; else echo ' odd'; ?>">
							<div class="col"><?php echo Libis_get_first_image_exhibit(exhibit_builder_get_current_exhibit());?></div>
				    		<div class="rondleidingContent">
					    		<h2><?php echo link_to_exhibit(); ?></h2>
					    		<?php
				    				//remove heading tags to get a readable snippet
				    				$description = preg_replace('~<h2>.*?</h2>~is', '', exhibit('description'));
				    				$description = preg_replace('~<h3>.*?</h3>~is', '', $description);
				    				$description = preg_replace('~<h1>.*?</h1>~is', '', $description);
				    			?>
					    		<p><?php echo snippet($description,0,300); ?></p>
					    		<p><a href="<?php echo exhibit_builder_exhibit_uri();?>" class="more">Start de Rondleiding</a></p>
					    	</div>
				    	</div>
				    <?php endwhile; ?>

                </div>
            </div>
        </div>


        <div class="clearfix">&nbsp;</div>


    </div>
	</div>
</div>

<?php foot(); ?>
