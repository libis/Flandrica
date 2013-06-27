<?php
/**
 * Timeline display partial.
 */
?>

<!-- Container. -->
<div id="<?php echo neatlinetime_timeline_id(); ?>" class="timeline-default" style="height: 600px; margin-top: 20px; margin-bottom: 20px;"></div>
<script>
    jQuery(document).ready(function() {
        NeatlineTime.loadTimeline(
            '<?php echo neatlinetime_timeline_id(); ?>',
            '<?php echo neatlinetime_json_uri_for_timeline()."&".time().'/'; ?>'
        );
    });
</script>