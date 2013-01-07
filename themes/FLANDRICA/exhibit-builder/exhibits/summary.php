<?php head(array('title' => html_escape(exhibit('title')),'bodyid'=>'exhibit','bodyclass'=>'rondleidingen')); ?>
 <div class="clearfix"></div>
    <div id="breadcrumb">
    	<ul>
        	<li><a href="<?php echo html_escape(uri('')); ?>">Home</a></li>
              <li>
                     <a href="<?php echo html_escape(uri('exhibits/browse/'));?>">Rondleidingen</a>
                </li>
            <li><?php echo html_escape($exhibit['title']); ?></li>
        </ul>
    </div>

    <div class="clearfix"></div>
    <div id="style_two">
    <div id="wrapper" class="cf">
    	<div id="container">
            <div id="content">
                <div id="main" class="padding-left-20 padding-right-20 article">
                	<h1><?php echo html_escape(exhibit('title')); ?></h1>
                    <div class="black">
                    	<?php
                    	foreach ($exhibit->Sections as $key => $exhibitSection) {
                    		if ($exhibitSection->hasPages()) {
                    			$uri = exhibit_builder_exhibit_uri($exhibit, $exhibitSection);
                    			break;break;
                    		}
                    	}
                    	?>
                        <a class="rondleidingStart" href="<?php echo $uri;?>">
                            <figure>
                            	<?php echo Libis_get_first_image_exhibit($exhibit,270,'view',true);?>
                            </figure>
                            <img src="<?php echo img('start_btn.png');?>" class="start_btn left" />
                        </a>
	                    <?php echo exhibit('description'); ?>

	                    <?php
	                    	if($uri){
	                    		echo '<a href="'.$uri. '" class="more">Start de rondleiding</a>';
	                    	}
	                    ?>


                    <div class="clearfix">&nbsp;</div>
                </div>


                </div>
            </div>
        </div>

        <div id="sidebar">
                <nav id="rondleiding_quicknav">
                    <h2>In deze rondleiding</h2>
                    <?php echo exhibit_builder_section_nav(); ?>

                </nav>
                <h1>Colofon</h1>
				<?php echo html_escape(exhibit('credits')); ?>
		</div>

        <div class="clearfix">&nbsp;</div>
    </div>
	</div>

</div>
<?php foot(); ?>
