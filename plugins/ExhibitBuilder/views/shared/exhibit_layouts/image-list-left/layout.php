<?php
	for ($i=1; $i <= 8; $i++):
	if(($text = exhibit_builder_page_text($i)) || exhibit_builder_use_exhibit_page_item($i)): ?>
<article class="clearfix">
	<div class="left" width="186">
		<?php if(exhibit_builder_use_exhibit_page_item($i)):?>
    	<figure class="black">
        	<?php
        		$pid = exhibit_builder_get_current_page()->ExhibitPageEntry[(int) $i]->thumbnail;
        		echo link_to_item(digitool_get_view_for_exhibit(exhibit_builder_page_item($i), $pid ,150),array(),'show',exhibit_builder_page_item($i));
        	?>
            <figcaption><?php echo exhibit_builder_exhibit_display_caption($i); ?></figcaption>
        </figure>
        <?php endif; ?>
    </div>
    <div class="mainColumnWide right">
    	<?php if($text): ?>
        	<?php echo $text; ?>
        <?php endif; ?>
	</div>
</article>
<?php endif; endfor;?>