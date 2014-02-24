<?php
$head = array('bodyclass' => 'image-manager-index',
              'title' => html_escape(__('Image Manager')));
echo head($head);
$elfinder_base_uri = WEB_PLUGIN."/ImageManager/view/javascript/elfinder/";
?>

<? //echo $this->enableElFinder();?>

<script type="text/javascript">
    jQuery(function(){
        var elf = jQuery('#elfinder').elfinder({
            // lang: 'ru', // language (OPTIONAL)
            url : '<?php echo uri('/image-manager/connector') ?>', // connector URL (REQUIRED)           
            resizable: false
           
        }).elfinder('instance');
    });
</script>

<div id="elfinder"></div>

<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>

<?php
echo foot();
?>
