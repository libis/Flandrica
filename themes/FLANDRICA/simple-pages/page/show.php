<?php
$bodyclass = 'nl';
if (simple_pages_is_home_page(get_current_simple_page())) {
    $bodyclass .= ' simple-page-home';
} ?>

<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(simple_page('slug')))); ?>

<?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>

 <?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>
	 <div id="breadcrumb">
	 	<?php echo Libis_display_breadcrumbs(); ?>
	  </div>
	  <div class="clearfix"></div>

<?php endif; ?>
    <div id="style_two">
    <div id="wrapper" class="cf">
    	<div id="container">
            <div id="content">
                <div id="main" class="padding-left-20 padding-right-20 article">
                	<h1><?php echo html_escape(simple_page('title')); ?></h1>
                	 <?php echo eval('?>' . simple_page('text')); ?>
                </div>
            </div>
        </div>
        <div id="sidebar">
        	<img src="<?php echo img('info-campagne-b1-275.png');?>"/>
	</div>
        <div class="clearfix">&nbsp;</div>
    </div>
	</div>

</div>
<?php endif;echo foot(); ?>