<?php head(array('title' => 'Contact', 'bodyid'=>'items','bodyclass' => 'show')); ?>
<div class="clearfix"></div>

<div id="style_three">
    <div id="wrapper" class="cf">
    	<div id="container">
            <div id="content">
            	<div id="main" class="padding-left-20 padding-right-20">
				<h1><?php echo htmlspecialchars(get_option('simple_contact_form_thankyou_page_title')); // Not HTML ?></h1>
					<?php echo get_option('simple_contact_form_thankyou_page_message'); // HTML ?>
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
		</div>
	</div>
	</div>
</div>
<?php foot(); ?>