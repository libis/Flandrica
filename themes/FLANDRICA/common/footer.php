    <?php
	if ($formActionUri):
	    $formAttributes['action'] = $formActionUri;
	else:
	    $formAttributes['action'] = uri(array('controller'=>'items',
	                                          'action'=>'browse'));
	endif;
	$formAttributes['method'] = 'GET';
	?>
    <div id="searchForm">
    <div class="wrapper">
        <h3>Geavanceerd zoeken</h3><a href="#" class="frm_close">Sluiten</a>
        <form <?php echo _tag_attributes($formAttributes); ?>>
                <fieldset>
                    <div class="row">
                    	<?php echo label('keyword-search', __('Search for Keywords')); ?>
                        <?php echo text(array(
		                    'name' => 'search',
		                    'size' => '40',
		                    'id' => 'keyword-search',
		                    'class' => 'textinput'), @$_REQUEST['search']); ?>
                    </div>
                    <div class="row">
                        <label><?php echo __('Narrow by Specific Fields'); ?></label>

			            <?php
			            // If the form has been submitted, retain the number of search
			            // fields used and rebuild the form
			            if (!empty($_GET['advanced'])) {
			                $search = $_GET['advanced'];
			            } else {
			                $search = array(array('field'=>'','type'=>'','value'=>''));
			            }

			            //Here is where we actually build the search form
			            foreach ($search as $i => $rows): ?>
			            	<div class="filterRow" rel="<?php echo $i;?>">

			                    <?php
			                    //The POST looks like =>
			                    // advanced[0] =>
			                    //[field] = 'description'
			                    //[type] = 'contains'
			                    //[terms] = 'foobar'
			                    //etc
			                    echo "<div class='col'>";
			                    echo select_element(
			                        array('name' => "advanced[$i][element_id]"),
			                        @$rows['element_id'],
			                        null,
			                        array('record_types' => array('Item', 'All'),
			                              'sort' => 'alphaBySet'));
			                    echo "</div>";
			                    echo "<div class='col'>";
			                    echo select(
			                        array('name' => "advanced[$i][type]"),
			                        array('contains' => __('contains'),
			                              'does not contain' => __('does not contain'),
			                              'is exactly' => __('is exactly'),
			                              'is empty' => __('is empty'),
			                              'is not empty' => __('is not empty')),
			                        @$rows['type']
			                    );
			                    echo "</div>";
			                    echo "<div class='col'>";
			                    echo text(
			                        array('name' => "advanced[$i][terms]",
			                              'size' => 20),
			                        @$rows['terms']);
			                    echo "</div>";?>

			                </div>
				            <?php endforeach; ?>
							<div class="filterRow"> <a href="#" class="frm_plus"><?php echo __('Add a Field'); ?></a></div>
							<button type="button" class="add_search"><?php echo __('Add a Field'); ?></button>
                     </div>
                    <div class="row">
                        <div class="col">
                            <label for="filterID">Zoeken op ID</label>
                            <?php echo text(
			                    array('name' => 'range',
			                          'size' => '40',
			                          'class' => 'filterID'),
			                    @$_GET['range']); ?>

                        </div>
                        <div class="col">
                            <label for="filterCollectie"><?php echo label('collection-search', __('Search By Collection')); ?></label>
                            <?php
			                    echo select_collection(array(
			                        'name' => 'collection',
			                        'id' => 'cfilterCollectie'
			                    ), @$_REQUEST['collection']);
			                ?>

                        </div>
                        <div class="col">
                            <label for="filterType"> <?php echo label('item-type-search', __('Search By Type')); ?></label>
                            <?php
                   				echo select_item_type(array('name'=>'type', 'id'=>'filterType'),
                        		@$_REQUEST['type']);
                   			?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="filterTags"><?php echo label('tag-search', __('Search By Tags')); ?></label>
                            <?php
		                    echo text(array(
		                        'name' => 'tags',
		                        'size' => '40',
		                        'id' => 'filterTags',
		                        'class'=>'textinput'),
		                    @$_REQUEST['tags']); ?>
                        </div>
                        <div class="col">
                            <label for="filterFeatured"> <?php echo label('featured', __('Featured/Non-Featured')); ?></label>

                            <?php echo select(array('name' => 'featured', 'id' => 'filterFeatured'),
		                    array('1' => __('Only Featured Items'),
		                          '0' => __('Only Non-Featured Items'))); ?>
                        </div>
                    </div>
                    <div class="row">
                        <input type="submit" value="zoeken" class="frm_search"/>
                    </div>
                </fieldset>
        </form>
    </div>
    </div>
    <footer>
        <div id="footerWrapper">
        <?php
        
        if($_SESSION['lang']){
            $lang = $_SESSION['lang']."/";
            if($lang == "nl/"){$lang="";}
        }else{
            $lang = "";
        }
        
        $pages = get_db()->getTable('SimplePagesPage')->findAllPagesOrderBySlug();
       
        ?>    
        <ul>           
            <?php if(Libis_page_exists($lang.'over-flandrica-be',$pages)){?>
                <li><a href="<?php echo uri($lang.'over-flandrica-be/'); ?>"><?php echo __("About Flandrica");?></a></li>
            <?php } ?>
            <?php if(Libis_page_exists($lang.'partners',$pages)){?>    
                <li><a href="<?php echo uri($lang.'partners/'); ?>"><?php echo __("Partners");?></a></li>
            <?php } ?>
            <?php if(Libis_page_exists($lang.'colofon',$pages)){?>
                <li><a href="<?php echo uri($lang.'colofon/'); ?>"><?php echo __("Colophon");?></a></li>
            <?php } ?>
            <?php if(Libis_page_exists($lang.'gebruiksvoorwaarden',$pages)){?>
                <li><a href="<?php echo uri($lang.'gebruiksvoorwaarden/'); ?>"><?php echo __("Conditions of use");?></a></li>
            <?php } ?>
            <li class="last"><a href="<?php echo uri('contact/'); ?>"><?php echo __("Contact");?></a></li>
            
        </ul>
       
        <div class="madeby">&copy; <a href="<?php echo __("http://www.vlaamse-erfgoedbibliotheek.be"); ?>"><?php echo __('Vlaamse Erfgoedbibliotheek');?></a></div>
        	<div class="sponsors">
        		<div style="float:right;">
                            <a href="<?php echo uri($lang.'gebruiksvoorwaarden/'); ?>">
                                <img src="<?php echo img("logobalk-cc.png");?>" title="CC BY-NC-SA 2.0" alt="Logo CreativeCommons CC BY-NC-SA 2.0" /></a>
                            <br>
                            <a href="http://www.vlaanderen.be">
                                <img src="<?php echo img("footer_03.jpg");?>" title="<?php echo __("Met steun van de Vlaamse overheid");?>" alt="<?php echo __("Logo Met steun van de Vlaamse overheid");?>" /></a>
        		</div>
                        
                        <a href="<?php echo __("http://www.vlaamse-erfgoedbibliotheek.be"); ?>"><img src="<?php echo img("logobalk-veb.png");?>" title="<?php echo __("Een initiatief van de Vlaamse Erfgoedbibliotheek");?>" alt="<?php echo __('Logo van de Vlaamse Erfgoedbibliotheek');?>"></a>
    			
                        <a href="<?php echo uri($lang.'partners/'); ?>"><img src="<?php echo img("logobalk-partners.png");?>" title="<?php echo __("De partners van Flandrica.be");?>" alt="<?php echo __("Logo's van de partners van Flandrica.be");?>"/></a>
    		</div>
        </div>
        <?php echo ga_footer();?>
    </footer>
</body>
</html>