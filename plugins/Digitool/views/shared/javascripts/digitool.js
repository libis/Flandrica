// Make the Find By Address button lookup the geocode of an address and add a marker.
    jQuery('#digitool_search').bind('click', function (event) {
        var search = jQuery('#fileUrl').val();
        that.findAddress(address);

        //Don't submit the form
        event.stopPropagation();
        return false;
    });