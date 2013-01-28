<?php //var_dump($this);?>
<div class="resultNav">
	<ul class="listNav">
	<?php $array = Zend_Registry::get('pagination');?>
		  <li><a href="<?php echo html_escape($this->url(array('page' => 0), null, $_GET)); ?>"><img src="<?php echo img("search_nav_first.png");?>" /></a></li>
          <li><a href="<?php echo html_escape($this->url(array('page' => $this->previous), null, $_GET)); ?>"><img src="<?php echo img("search_nav_back.png");?>"  /></a></li>
          <li>Pagina <?php echo $array['page'];?>  van <?php echo $this->pageCount;?></li>
          <li><a href="<?php echo html_escape($this->url(array('page' => $this->next), null, $_GET)); ?>"><img src="<?php echo img("search_nav_next.png");?>"  /></a></li>
          <li><a href="<?php echo html_escape($this->url(array('page' => $this->pageCount), null, $_GET)); ?>"><img src="<?php echo img("search_nav_last.png");?>"  /></a></li>
	</ul>
</div>

