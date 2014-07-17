<?php
session_start();
?>
<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <?php if ( $description = settings('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

    <title><?php echo settings('site_title'); echo isset($title) ? ' | ' . strip_formatting($title) : ''; ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- EXTRA RSS -->
    <!--
        <link rel="alternate" type="application/rss+xml" title="Flandrica RSS Tentoonstellingen" href="/items/browse?output=rss2&amp;type=tentoon" />
	<link rel="alternate" type="application/atom+xml" title="Flandrica Atom Tentoonstellingen" href="/items/browse?output=atom&amp;type=tentoon" />
	<link rel="alternate" type="application/rss+xml" title="Flandrica RSS Objecten" href="/items/browse?output=rss2&amp;type=object" />
	<link rel="alternate" type="application/atom+xml" title="Flandrica Atom Objecten" href="/items/browse?output=atom&amp;type=object" />
    -->    
	<!-- YAHOO RESET CSS -->
    <!-- removes and neutralizes the inconsistent default styling of HTML elements -->
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css" />

    <!-- humans.txt -->
	<link rel="author" href="Wax Interactive" />

	<!-- Favicons and the like (avoid using transparent .png) -->
	<link rel="shortcut icon" href="<?php echo img("icon.ico");?>" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo img("favicon.gif");?>" />

    <!-- Plugin Stuff -->
    <?php plugin_header(); ?>

    <!-- Stylesheets -->
    <?php
    queue_css('style');
    queue_css('lightbox');

    display_css();
    ?>
    <!-- JavaScripts -->

	<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
	<?php queue_js('modernizr.custom.56255');?>
	<?php queue_js('jquery.roundabout.min');?>
	<?php queue_js('jquery.roundabout-shapes.min');?>	
	<?php queue_js('custom_form_elements');?>
	<?php queue_js('lightbox');?>
	<script>function LightboxDelegate(url,caption) {
		var objLink = document.createElement('a');
		objLink.setAttribute('href',url);
		objLink.setAttribute('rel','lightbox');
		objLink.setAttribute('title',caption);
		lightbox.start(objLink);
	}</script>
	<?php queue_js('init');?>
    <?php echo display_js(); ?>

</head>
<?php
	if ($_SERVER["REQUEST_URI"] == '/') {
		$bodyclass = 'home';
		//echo $_SERVER["REQUEST_URI"];
	}
?>
<body <?php echo isset($bodyid) ? ' id="'.$bodyid.'"' : ''; ?><?php echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : ''; ?>>
<?php plugin_body(); ?>
	<div class="wrapper">
    	<header>
    		<div class="col logo">
		            <a href="<?php echo uri('');?>" class="logo">
		                <img src="<?php echo img('logo.gif');?>" alt="Flandrica" title="Flandrica">
		            </a>
		        </div>
		    <div class="col headerstuff">
		        	<!-- <div class="grid-geavanceerd col" style="width:124px; /*border-right:1px solid #e7e7e7;*/ margin:0; padding:0 20px 0 0;"><p><a href="#"  id="search_advanced">GEAVANCEERD ZOEKEN</a></p></div> -->
					<div class="grid-lang col" style="width:85px; padding:0 0 0 20px; margin:0;"><!--<p><a href="#" class="active">NL</a> &#124; <a href="#">FR</a> &#124; <a href="#">EN</a></p>--></div>
		            <?php echo Libis_language_widget();?>
                            <div class="search">
		                <form  action="<?php echo uri('solr-search/results/')?>" method="GET">
		                    <input type="text" class="searchfield" title="Zoeken..." alt="Zoeken..." value="<?php echo __('SEARCH...');?>" name="solrq" maxlength="128" onClick="this.value='';" />
							<input class="submitSimple" type="submit" value="" />
		                </form>

        			</div>
		        </div>

    		<?php //plugin_page_header(); ?>
			</header>
			<nav id="mainNav">
		    	<ul >
		        	<li class="home"><a href="<?php echo uri(''); ?>">&nbsp;</a></li>
		            <li class="collectie"><a href="<?php echo uri('solr-search/results/?solrfacet=collection:*'); ?>" class="dropdown"><?php echo __('the');?> <strong><?php echo __('collection');?></strong></a>
		            	<?php

							$collections = get_collections();
							if($collections){
								asort($collections);
								echo "<ul>";
								foreach ($collections as $collection) {
									echo "<li><a href='".uri('solr-search/results/?solrfacet=collection:"'.$collection->name.'"')."'>".$collection->name."</a></li>";

								}
								echo "</ul>";
							}

						?>

		            </li>
		            <li class="thema"><a href="<?php echo uri('themas')?>"><?php echo __('by');?> <strong><?php echo __('theme');?></strong></a>

		            </li>
		            <li class="plaats"><a href="<?php echo uri('geolocation/map/browse/')?>"><?php echo __('by');?> <strong><?php echo __('location');?></strong></a>

		            </li>
		            <li class="periode"><a href="<?php echo uri('neatline-time/timelines/show/1/')?>" ><?php echo __('by');?> <strong><?php echo __('periode');?></strong></a>

		            </li>
		            <li class="rondleiding last"><a href="<?php echo html_escape(uri('exhibits/browse/'));?>" class="dropdown"><?php echo __('the');?> <strong><?php echo __('tours');?></strong></a>
		        	<?php
                                    $exhibits = exhibit_builder_get_exhibits();
                                    $lang = libis_get_language();
                                        if($exhibits){
                                            echo "<ul>";
                                        foreach($exhibits as $exhibit){
                                            if(substr( $exhibit->slug, 0, 2 ) === $lang || ($lang=='nl' && substr( $exhibit->slug, 0, 2 ) !== 'en')):
                                                echo '<li><a href="'.exhibit_builder_exhibit_uri($exhibit).'">'.$exhibit->title.'</a></li>';
                                            endif;
                                        }
                                        echo "</ul>";
                                        }
		        	?>
                            </li>
		        </ul>
		    </nav>