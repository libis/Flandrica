<!-- Sam aangepast van 336 naar 536 -->
<div class="right" width="536">
	<figure class="black exhibit-image">
		<?php
			if(exhibit_builder_use_exhibit_page_item(1)):
				$pid = exhibit_builder_get_current_page()->ExhibitPageEntry[(int) 1]->thumbnail;
			// aangepast van 300 naar 500
        		echo link_to_item(digitool_get_view_for_exhibit(exhibit_builder_page_item(1), $pid ,500),array(),'show',exhibit_builder_page_item(1));
			endif;
		?>
		<figcaption><?php echo exhibit_builder_exhibit_display_caption(1); ?></figcaption>
	</figure>
</div>
<div class="mainColumn">
	<?php echo exhibit_builder_page_text(1); ?>
</div>