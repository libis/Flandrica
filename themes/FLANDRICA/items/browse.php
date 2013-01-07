<?php
Zend_Session::start();
if(!Zend_Registry::isRegistered('session'))
{
	$session = new Zend_Session_Namespace('zoek');
	Zend_Registry::set('session', $session);
	//$logger->log(print_r($session), Zend_Log::INFO);
}

$pageTitle = __('Browse Items');
head(array('title'=>$pageTitle,'bodyid'=>'items','bodyclass' => 'browse'));

//set style
if($_GET['style']){
	$style=$_GET['style'];
}
else{
	if($_GET['tags'])
		$style='table';
	else
		$style='list';
}
?>

<div id="primary">
 <?php if(!empty($facets)): ?>
<?php $query = SolrSearch_QueryHelpers::getParams(); ?>
<div class="solr_facets_container">
<h3>Limit your search</h3>
<div class="solr_facets">
<?php foreach ((array)$results->facet_counts->facet_fields as $facet => $values): ?>
<?php $props = get_object_vars($values); ?>
<?php if (!empty($props)): ?>
<h4 class="facet"><?php echo SolrSearch_QueryHelpers::parseFacet($facet); ?></h4>
<ul>
<?php foreach($values as $label => $count): ?>
<li><?php echo SolrSearch_QueryHelpers::createFacetHtml($query, $facet, $label, $count); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
	<!-- MOET VERVANGEN WORDEN DOOR 'if type == afbeelding' of iets dergelijks -->
	<?php if($_GET["type"] !="Nieuwsbericht"){?>


    <h1><?php echo $pageTitle;?> <?php echo __('(%s total)', total_results()); ?></h1>

    <!-- ZOEK GESCHIEDENIS  -->
    <div class="browse-zoek-historiek">
    <h4>Zoekhistoriek</h4>
		<?php
			if(!empty($_GET['search'])){
				$session = Zend_Registry::get('session');
				$session->historiek[] = curPageURL();
				$session->historiek = array_unique($session->historiek);
				//max 3 zoekopdrachten in het geheugen
				if(sizeof($session->historiek) > 3)
					$session->historiek = array_slice($session->historiek,1);
			}
			$i=1;
			if(sizeof($session->historiek) > 0):
				foreach($session->historiek as $historiek){
					echo "<a href='".$historiek."'>Zoekopdracht ".$i."</a>";
					$i++;
				}
			else:
				echo"<p>Er is geen zoekhistoriek</p>";
			endif;
		?>
		<p>
		<a class="jqbookmark" href="<?php echo curPageURL(); ?>">Save URL query</a>
		</p>
	</div>

    <p><?php //echo uri($_SERVER["REQUEST_URI"]);	?></p>

	<div class="browse-form">
		<form method="GET" action="">
			<input <?php if($style=="list" || $style==''){echo "checked";} ?> type="radio" name="style" value="list" /><label> Lijst</label>
			<input <?php if($style=="table"){echo "checked";} ?> type="radio" name="style" value="table" /><label> Tabel</label>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Sorteren op </label>

			<select name="sort_field">
				<option <?php if($_GET['sort_field']=="Dublin Core,Title"){echo "selected='selected'";} ?> value="Dublin Core,Title">Titel</option>
				<option <?php if($_GET['sort_field']=="Dublin Core,Creator"){echo "selected='selected'";} ?> value="Dublin Core,Creator">Auteur</option>
				<option  <?php if($_GET['sort_field']=="Dublin Core,Date Created"){echo "selected='selected'";} ?> value="Dublin Core,Date Created">Jaar van publicatie</option>
				<option  <?php if($_GET['sort_field']=="added"){echo "selected='selected'";} ?> value="added">Datum toegevoegd</option>
			</select>

			<input type="submit" value="OK">
		</form>
	</div>

    <div id="pagination-top" class="pagination"><?php echo pagination_links(); ?></div>
	<div class="browse-nav">
		<h2>Verfijnen</h2>
		<div class="browse-nav-js">
			<h3 class="active">Instelling</h3>
			<ul><li>test</li></ul>
			<h3>Periode</h3>
			<ul><li>test</li></ul>
			<h3>Materiaaltype</h3>
			<ul><li>test</li></ul>
			<h3>Andere?</h3>
			<ul><li>test</li></ul>
		</div>
	</div>


	<!-- BEGIN ITEM LOOP-->
	<div class="browse-<?php echo $style;?>">
		<?php
		while (loop_items()):
			if(!item_has_type('Nieuwsbericht')):?>

				<div class="item hentry">
					<div class="item-meta">

					<?php if (item_has_thumbnail() || digitool_item_has_digitool_url(get_current_item())): ?>
					<div class="item-img">
						<?php echo link_to_item(digitool_thumbnail(get_current_item(),$fileFirst = true, $size = "150",$class="",$alt="")); ?>

					</div>
					<?php endif; ?>
					<!-- Titel -->
					<h4><?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?></h4>
					<div class="item-description">
					<!-- Auteur -->
					<?php if ($author = item('Dublin Core', 'Creator')): ?>
						<p><?php echo $author; ?></p>
					<?php endif; ?>

					<!-- Plaats, drukker, jaar -->
					<p>

					<?php $loc = geolocation_get_location_for_item(get_current_item(),true);
						if($loc['address']){
							echo $loc['address'];}
					?>
					<?php if ($pub = item('Dublin Core', 'Publisher')): ?>
						<?php echo $pub; ?>
					<?php endif; ?>

					<?php if ($date = item('Dublin Core', 'Date Created')): ?>
						<?php echo date("Y",$date); ?>
					<?php endif; ?>

					</p>


					<!-- Verantwoording -->
					<?php if ($rights = item('Dublin Core', 'Rights')): ?>
						<?php echo $rights; ?>
					<?php endif; ?>


					<?php if (item_has_tags()): ?>
					<div class="tags"><p><strong><?php echo __('Tags'); ?>:</strong>
						<?php echo item_tags_as_string(); ?></p>
					</div>
					<?php endif; ?>

					<?php echo plugin_append_to_items_browse_each(); ?>
					</div>
					</div><!-- end class="item-meta" -->
				</div><!-- end class="item hentry" -->

		<?php endif;
		endwhile; ?>
		<!-- EINDE LOOP ) -->
	</div>

    <div id="pagination-bottom" class="pagination"><?php echo pagination_links(); ?></div>

    <?php echo plugin_append_to_items_browse(); ?>

    <script>
	jQuery(document).ready(function() {
		jQuery(".browse-nav-js").collapse({show: function(){
			this.animate({
				opacity: 'toggle',
                height: 'toggle'
			}, 300);
			},
		hide : function() {
			this.animate({
				opacity: 'toggle',
				height: 'toggle'
			}, 300);
		}
		});
	});
	</script>

	<?php } //endif?>

	<!-- BEGIN NIEUWS -->
	<?php if($_GET["type"] =="Nieuwsbericht"){?>
		<h1>Nieuws <?php echo __('(%s total)', total_results()); ?></h1>
		<?php while (loop_items()):?>
			<h4><?php echo item('Dublin Core','Title') ?></h4>
			<p><?php echo  item('Item Type Metadata','Van') ?></p>
			<p><?php echo item('Dublin Core','Description') ?></p>
		<?php
		endwhile;?>

		<div id="pagination-bottom" class="pagination"><?php echo pagination_links(); ?></div>

	<?php }?>
	<!--  EINDE NIEUWS -->

</div><!-- end primary -->

<?php foot(); ?>
