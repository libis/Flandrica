<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

  $pageTitle = __('Browse Items'); //TODO: Should this be browse items?
  head(array('title' => $pageTitle, 'id' => 'items', 'bodyclass' => 'browse'));

  $session = new Zend_Session_Namespace('style');

  $style= $session->style;
  $perPage = get_option('solr_search_rows');
  $pgnum = $_POST['pgnum'];

?>
 <div class="clearfix"></div>
    <div id="style_two">
    <div id="wrapper" class="cf">

    	<div class="topresults">
            <div class="resultCount"><?php echo __('%s RESULTS', $results->response->numFound); ?></div>

            <div class="resultDisplay">
                <span class="weergaveLabel"><?php echo __("Display"); ?></span>
                <?php
	            	//get url
	            	$uri = SolrSearch_ViewHelpers::getBaseUrl();
        			$params = SolrSearch_QueryHelpers::getParams();
	                $link = $uri . '?solrq=' . $params['q'] . '&solrfacet=' . $params['facet'];
                ?>
                <a href='<?php echo $link."&style=list";?>'><img src="<?php echo img("search_weergave2.png");?>" /></a>
                <a href='<?php echo $link."&style=gallery";?>'><img src="<?php echo img("search_weergave3.png");?>" /></a>
            </div>
            <!--  <div class="resultSort">
                Sorteer op
                <select>
                  <option value="sort1">Naam</option>
                  <option value="sort2">Populariteit</option>
                  <option value="sort3">Datum</option>
                </select>
            </div>-->
            <div class="resultsPerPage">
            	<form action='<?php echo curPageURL();?>' method="post">
                <select name="perPage" onchange="this.form.submit()">
                  <option <?php if($perPage==8){echo 'selected="selected"';}?> value="8"><?php echo __('%s results per page', 8); ?></option>
                  <option <?php if($perPage==16){echo 'selected="selected"';}?> value="16"><?php echo __('%s results per page', 16); ?></option>
                  <option <?php if($perPage==32){echo 'selected="selected"';}?> value="32"><?php echo __('%s results per page', 32); ?></option>
                </select>
                </form>
            </div>
             <?php echo pagination_links(array('partial_file' => 'common/pagination.php','per_page'=>$per_page)); ?>

        </div>
    	<div id="container">
            <div id="content">
            <?php if($style=='gallery'){?>
            	<div id="main" class="gallery ">
                	<div class="blok odd" style="width:100%;"><?php echo SolrSearch_QueryHelpers::removeFacets(); ?></div>
					<div id="gallery-container">
	                 	<?php echo $this->pageCount; ?>
	               	 	<?php $count =1;?>
						<?php foreach($results->response->docs as $doc): ?>
							<?php $count++;?>
							<?php $item = get_item_by_id(preg_replace ( '/[^0-9]/', '', $doc->__get('id')));?>
							<?php if($item){?>
						    <div class="cell" id="solr_<?php echo $doc->__get('id'); ?>">
						    	<?php echo link_to_item(digitool_get_thumb($item,true,false,'140'),array(),'show',$item);?>
								<?php
						          //auteur
						          $itemDescription = item('Dublin Core', 'Creator',array(),$item) ? item('Dublin Core', 'Creator',array('delimiter'=>', '),$item)."<br>" : '';
						          //plaats
						          $itemDescription .= item('Item Type Metadata', 'Creatie plaats',array(),$item) ? item('Item Type Metadata', 'Creatie plaats',array('delimiter'=>', '),$item)."<br>" : '';
								  //periode
						          $itemDescription .= item('Item Type Metadata', 'Periode',array(),$item) ? item('Item Type Metadata', 'Periode',array(),$item)."<br>" : '';
						          //verantwoording

						        ?>
						    	<div class='tooltip' ><span class='slidePlace'><?php echo $itemDescription; ?></span></div>

						        <div class="content">
						          <h2><?php echo link_to_item(snippet(item('Dublin Core', 'Title',array(),$item),0,35),array(),'show',$item);?></h2>
						        </div>
						    </div>
						<?php }else{ ?>
						<div class="cell  <?php if ($count%2==1) echo ' even'; else echo ' odd'; ?>" id="solr_<?php echo $doc->__get('id'); ?>">
							<div class="col"></div>
					        <div class="content">
					          <h2><a href="<?php echo item_uri('show',$item);?>" ><?php echo item('Dublin Core', 'Title',array(),$item);?></a></h2>


					          <?php if($results->responseHeader->params->hl == true): ?>
					              <?php echo SolrSearch_ViewHelpers::displaySnippets($doc->id, $results->highlighting); ?>
					          <?php endif; ?>

					          <?php $tags = $doc->__get('tag'); ?>
					          <?php if($tags): ?>
								<br>
						           <p><strong>Tags:</strong>
						           <?php echo SolrSearch_ViewHelpers::tagsToStrings($tags); ?>
									</p>
					            <?php endif; ?>
					    	</div>
					    </div>

						<?php } ?>
					    <?php endforeach; ?>
					</div>
              	</div>
            <?php }else{?>
				<div id="main" class="zebra">
                	<div class="blok odd cf" ><?php echo SolrSearch_QueryHelpers::removeFacets(); ?></div>
                 <?php echo $this->pageCount; ?>
                <?php $count =1;?>
					<?php foreach($results->response->docs as $doc): ?>
						<?php $count++;?>
						<?php $item = get_item_by_id(preg_replace ( '/[^0-9]/', '', $doc->__get('id')));?>
					    <?php if($item){?>
					    <div class="blok  <?php if ($count%2==1) echo ' even'; else echo ' odd'; ?>" id="solr_<?php echo $doc->__get('id'); ?>">
					    	<div class="col"><?php echo digitool_get_thumb($item,true,false,'140');?></div>
					        <div class="content">
					          <h2><a href="<?php echo item_uri('show',$item);?>" ><?php echo item('Dublin Core', 'Title',array(),$item);?></a></h2>
					          <?php
					          //auteur

					          $itemDescription = item('Dublin Core', 'Creator',array(),$item) ? item('Dublin Core', 'Creator',array('delimiter'=>', '),$item)."<br>" : '';
					          //publisher
					          $itemDescription .= item('Dublin Core', 'Publisher',array(),$item) ? item('Dublin Core', 'Publisher',array('delimiter'=>', '),$item)."<br>" : '';
							  //plaats
					          $itemDescription .= item('Item Type Metadata', 'Creatie plaats',array(),$item) ? item('Item Type Metadata', 'Creatie plaats',array('delimiter'=>', '),$item)."<br>" : '';
							  //periode
					          $itemDescription .= item('Item Type Metadata', 'Periode',array(),$item) ? item('Item Type Metadata', 'Periode',array(),$item)."<br>" : '';
					          //verantwoording
					          $itemDescription .= item('Item Type Metadata', 'Verantwoording',array(),$item) ? item('Item Type Metadata', 'Verantwoording',array('delimiter'=>', ','snippet'=>'150'),$item)."<br>" : '';
							  echo $itemDescription;

					          ?>

					          <?php //if($results->responseHeader->params->hl == true): ?>
					              <?php //echo SolrSearch_ViewHelpers::displaySnippets($doc->id, $results->highlighting); ?>
					          <?php //endif; ?>

					          <?php $tags = $doc->__get('tag'); ?>
					          <?php if($tags): ?>
								<br>
						           <p><strong>Tags:</strong>
						           <?php echo SolrSearch_ViewHelpers::tagsToStrings($tags); ?>
									</p>
					            <?php endif; ?>

					           <p><a href="<?php echo item_uri('show',$item);?>" class="more"><?php echo __("go to document");?></a></p>

					    	</div>

					    </div>

						<?php }else{ ?>
						<div class="blok  <?php if ($count%2==1) echo ' even'; else echo ' odd'; ?>" id="solr_<?php echo $doc->__get('id'); ?>">
							<div class="col"></div>
					        <div class="content">
					          <h2><?php echo SolrSearch_ViewHelpers::createResultLink($doc); ?></h2>


					          <?php if($results->responseHeader->params->hl == true): ?>
					              <?php echo SolrSearch_ViewHelpers::displaySnippets($doc->id, $results->highlighting); ?>
					          <?php endif; ?>

					          <?php $tags = $doc->__get('tag'); ?>
					          <?php if($tags): ?>
								<br>
						           <p><strong>Tags:</strong>
						           <?php echo SolrSearch_ViewHelpers::tagsToStrings($tags); ?>
									</p>
					            <?php endif; ?>
					    	</div>
					    </div>

						<?php } ?>
				    <?php endforeach; ?>

              	</div>
              	<?php } ?>
		 	</div>
		</div>
        <nav id="sidebar" class="solr_facets">
        	<?php if(!empty($facets)): ?>
		      <?php $query = SolrSearch_QueryHelpers::getParams(); ?>

		        <?php foreach ((array)$results->facet_counts->facet_fields as $facet => $values): ?>
		           <?php //print_r($values);?>
		            <?php $props = get_object_vars($values); ?>
		            <?php if (!empty($props)): ?>
		            	<section>
		                <h3 style="clear:both;" class="facet"><?php echo __(SolrSearch_QueryHelpers::parseFacet($facet)); ?></h3>
		                    <ul>
							<?php foreach($values as $label => $count): ?>
		                        <li><?php echo SolrSearch_QueryHelpers::createFacetHtml($query, $facet, $label, $count); ?></li>
							<?php endforeach; ?>
		                    </ul>
		            	</section>
		            <?php endif; ?>
		        <?php endforeach; ?>

		    <?php endif; ?>


		</nav>

        <div class="clearfix">&nbsp;</div>

        <div class="topresults bottom">
             <?php echo pagination_links(array('partial_file' => 'common/pagination.php')); ?>

        </div>
    </div>
	</div>
</div>

<?php echo foot();?>
