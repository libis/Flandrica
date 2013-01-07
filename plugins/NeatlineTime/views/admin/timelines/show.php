<?php
/**
 * The show view for the Timelines administrative panel.
 */

queue_timeline_assets();
$head = array('bodyclass' => 'timelines primary', 
              'title' => __('Neatline Time | %s', timeline('title'))
              );
head($head);
?>
  <h1><?php echo timeline('title'); ?> <span class="view-public-page">[ <a href="<?php echo html_escape(public_uri('neatline-time/timelines/show/'.timeline('id'))); ?>"><?php echo __('View Public Page'); ?></a> ]</h1>
<?php if (has_permission(get_current_timeline(), 'edit')): ?>
<p id="edit-timeline" class="edit-button">
    <?php echo link_to_timeline(__('Edit Metadata'), array('class' => 'edit'), 'edit'); ?>
    <?php echo link_to_timeline(__('Edit Items Query'), array('class' => 'edit'), 'query'); ?>
</p>
<?php endif; ?>

<div id="primary">

    <!-- Construct the timeline. -->
    <?php echo $this->partial('timelines/_timeline.php'); ?>

    <?php if ($query = timeline('query')) : ?>
        <h2><?php echo __('Items Query'); ?></h2>
        <p><strong><?php echo __('The &#8220;%s&#8221; timeline displays items that match the following query:',timeline('title')); ?></strong></p>
        <?php echo neatlinetime_display_search_query(timeline('query')); ?>
    <?php endif; ?>

</div>
<?php foot(); ?>
