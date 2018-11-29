<?php
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = parse_url($url, PHP_URL_PATH);
$pathFragments = explode('/', $path);
$end = end($pathFragments);
$id = $end;

 ?>
<div class="mapsInfoWindow" style="display:hidden">
    <div class="infoWindow">
    <?php
    if(isset($id)):
      set_current_item(get_item_by_id($id));
      $item = get_current_item();
      if(digitool_item_has_digitool_url($item)){
          echo link_to_item(digitool_get_thumb($item, true, false,100,"bookImg"));
      }
      echo "<h2 class='bookTitle'>".link_to_item(item('Dublin Core', 'Title',array('snippet' => 25)))."</h2>";
      $itemDescription = item('Dublin Core', 'Creator') ? item('Dublin Core', 'Creator')."<br>" : '';
      $itemDescription .= item('Dublin Core', 'Publisher') ? item('Dublin Core', 'Publisher')."<br>" : '';

      $itemDescription .= item('Item Type Metadata', 'Creatie plaats') ? item('Item Type Metadata', 'Creatie plaats')."<br>" : '';

      $itemDescription .= item('Item Type Metadata', 'Periode') ? item('Item Type Metadata', 'Periode') : '';

      echo "<div class='bookAuthor'>".$itemDescription."</div>";
    endif;
    ?>
    </div>
</div>
