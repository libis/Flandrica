<?php
/**
 * The browse view for the Timelines administrative panel.
 */

$head = array('bodyclass' => 'timelines primary', 
              'title' => html_escape(__('Neatline Time | Browse Timelines')));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<p id="add-timeline" class="add-button"><a class="add" href="<?php echo html_escape(uri('neatline-time/timelines/add')); ?>"><?php echo __('Add a Timeline'); ?></a></p>
<div id="primary">
<?php echo flash(); ?>
<?php if (has_timelines_for_loop()) : ?>
<div class="pagination"><?php echo pagination_links(); ?></div>
<table>
    <thead id="timelines-table-head">
        <tr>
        <th><?php echo __('Title'); ?></th>
        <th><?php echo __('Description'); ?></th>
        <th><?php echo __('Edit Metadata'); ?></th>
        <th><?php echo __('Edit Item Query'); ?></th>
        <th><?php echo __('Delete'); ?></th>
        </tr>
    </thead>
    <tbody id="types-table-body">
<?php while (loop_timelines()) : ?>
        <tr>
            <td class="timeline-title"><?php echo link_to_timeline(); ?></td>
            <td><?php echo snippet_by_word_count(timeline('description'), '50'); ?></td>
            <td>
            <?php
            if (has_permission(get_current_timeline(), 'edit')) {
                echo link_to_timeline(__('Edit Metadata'), array('class' => 'edit'), 'edit');
            }
            ?>
            </td>
            <td>
            <?php
            if (has_permission(get_current_timeline(), 'query')) {
                echo link_to_timeline(__('Edit Item Query'), array('class' => 'query'), 'query');
            }
            ?>
            </td>
            <td>
            <?php
            if (has_permission(get_current_timeline(), 'delete')) {
                echo timeline_delete_button(get_current_timeline());
            }
            ?>
            </td>
        </tr>
<?php endwhile; ?>
    </tbody>
</table>
<?php else : ?>
    <p><?php echo __('There are no timelines.'); ?> <?php if (has_permission('NeatlineTime_Timelines', 'add')): ?><a href="<?php echo html_escape(uri('neatline-time/timelines/add')); ?>"><?php echo __('Add a Timeline'); ?>.</a><?php endif; ?></p>
<?php endif; ?>
</div>
<?php foot(); ?>
