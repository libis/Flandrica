<?php
/**
 * The public show view for Timelines.
 */
queue_timeline_assets();
$head = array('bodyclass' => 'timeline',
              'title' => timeline('title')
              );
head($head);
?>

<div class="clearfix"></div>
</div>
        <div id="style_one">
            <div id="wrapper" class="cf">
                <div class="wrapper">
                    <div id="timeline_container" class="timeline">
                        <?php echo $this->partial('timelines/_timeline.php'); ?>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
            </div>
        </div>
<?php foot();?>