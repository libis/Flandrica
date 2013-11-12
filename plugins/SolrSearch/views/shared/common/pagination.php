<SCRIPT LANGUAGE="javascript">
<!--
function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   myfield.form.submit();
   return false;
   }
else
   return true;
}
//-->
</SCRIPT>
<div class="resultNav">
    <ul class="listNav">           
    <form action='<?php echo curPageURL();?>' name='numbox' method='post'>
	<?php $array = Zend_Registry::get('pagination');?>
        <li><a href="<?php echo html_escape($this->url(array('page' => 0), null, $_GET)); ?>"><img src="<?php echo img("search_nav_first.png");?>" /></a></li>
        <li><a href="<?php echo html_escape($this->url(array('page' => $this->previous), null, $_GET)); ?>"><img src="<?php echo img("search_nav_back.png");?>"  /></a></li>
        <li><?php echo __("Page");?>           
            <input type="text" onKeyPress="return submitenter(this,event)" name="pgnum" value="<?php echo $array['page'];?>"></input>
            <input  type="submit" name="update" value=" Apply " 
            style="position: absolute; height: 0px; width: 0px; border: none; padding: 0px;"
            hidefocus="true" tabindex="-1"/>
            <?php echo __("of");?> <?php echo $this->pageCount;?></li>
        <li><a href="<?php echo html_escape($this->url(array('page' => $this->next), null, $_GET)); ?>"><img src="<?php echo img("search_nav_next.png");?>"  /></a></li>
        <li><a href="<?php echo html_escape($this->url(array('page' => $this->pageCount), null, $_GET)); ?>"><img src="<?php echo img("search_nav_last.png");?>"  /></a></li>
    </form>    
    </ul>
</div>

