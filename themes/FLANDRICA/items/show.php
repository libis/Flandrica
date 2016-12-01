<?php head(array('title' => item('Dublin Core', 'Title'), 'bodyid' => 'items', 'bodyclass' => 'show')); ?>
<?php if(item_has_type("Flandrica-object")): ?>
<div id="subnav">
    <ul>
        <li class="first back"><a href="javascript:history.go(-1)"><?php echo __('Terug naar overzicht') ?></a></li>
        <?php
        if(item('Item Type Metadata', 'Object instelling')):
        $objArray = item('Item Type Metadata', 'Object instelling', array('all' => true));
        if(sizeof($objArray)>1):?>
        <li class="raadplegen"><a class="raad-pop" href="#" ><?php echo __('Online raadplegen') ?></a></li>
        <?php else: ?>
        <li class="raadplegen"><a href="<?php echo item('Item Type Metadata', 'Object instelling') ?>" target="_blank"><?php echo __('Online raadplegen') ?></a></li>
        <?php endif;
        endif;
        ?>
        <li class="share"><a href="http://www.addthis.com/bookmark.php" style="text-decoration:none;"
                             class="addthis_button"><?php echo __('Delen en opslaan') ?></a>
        </li>
        <li class="print"><a href="#" onClick="window.print()"><?php echo __('Afdrukken') ?></a></li>
        <li class="last comment"><a href="<?php echo uri('contact/?onderwerp=Vraag stellen over object&message=Reactie op '.item('Dublin Core', 'Title')) ?>"><?php echo __('Reageren') ?></a></li>
    </ul>
</div>
<div class="clearfix"></div>

<div id="style_three">
    <div id="wrapper" class="cf">
        <div id="container">
            <div id="content">

                <div id="pages-thumbnails">
                <?php echo digitool_simple_gallery_flandrica($item, item('Item Type Metadata', 'Object instelling')); ?>
                </div>
                <?php
                // Dedup auteurs (bovenaangezet door Sam om dubbele code te vermijden
                $auteurs = "";
                if(item('Dublin Core', 'Creator')){
                $auteurs .= item('Dublin Core', 'Creator', array('delimiter' => '| '));
                if(item('Dublin Core', 'Contributor')){
                $auteurs .= "| ".item('Dublin Core', 'Contributor', array('delimiter' => '| '));
                }
                }else{
                if(!item('Dublin Core', 'Creator') && item('Dublin Core', 'Contributor')){
                $auteurs .= item('Dublin Core', 'Contributor', array('delimiter' => '| '));
                }
                }
                $array = explode('| ', $auteurs);
                $array = array_unique($array);

                $auteurs = implode('; ', $array);
                ?>
                <div id="main" class="padding-left-20 padding-right-20">

                    <!-- BEWAARINSTELLING -->
                    <?php
                    if ($Collection = get_collection_for_item()) {
                    echo '<div class="bewaarinstelling">' . $Collection->name . '</div>';
                    }
                    ?>

                    <h2>
<?php
echo item('Dublin Core', 'Title');
?>
                    </h2>
                    <div class="auteur">
                        <!-- Auteur, Illustrator -->
<?php
// aangepast door Sam
echo $auteurs;
?>
                    </div>
                    <div class="extra-left">
                        <!-- Verantwoording -->
<?php if (item('Item Type Metadata', 'Verantwoording')): ?>
                        <p><?php echo item('Item Type Metadata', 'Verantwoording'); ?></p>
<?php endif; ?>
                        <h3><a href="<?php echo item('Item Type Metadata', 'Object instelling') ?>" target="_blank" class="more"><?php echo __('Dit werk online raadplegen') ?></a></h3>
                    </div>
                    <div>
                        <ul class="extra-right">
                            <!-- TREFWOORDEN -->
                            <?php if (item_has_tags()):
                            $tags = get_tags(array('record' => $item), 20);
                            natcasesort($tags);
                            echo Libis_tag_string($tags, uri('solr-search/results/?style=gallery&solrfacet=tag:'));
                            endif; ?>
                        </ul>
                    </div>

                    <div class="blok1 left">
                        <table cellpadding="0" cellspacing="0" border="0" class="infofiche">
                            <tbody>
                                <!-- Titel-->
                                <?php if(item('Dublin Core', 'Title')): ?>
                                    <tr><td class="label"><?php echo __("Title") ?></td>
                                    <td><?php echo item('Dublin Core', 'Title'); ?></td></tr>
                                <?php endif; ?>
                                <?php if(item('Dublin Core', 'Alternative Title')): ?>
                                    <tr><td class="label"><?php echo __("Alternatieve titel") ?><td> <?php echo item('Dublin Core', 'Alternative Title'); ?></td></tr>
                                <?php endif; ?>
                                <!-- creator -->
                                <?php if(!empty($auteurs)) { ?>
                                <tr><td class="label"><?php echo __('Auteur') ?></td>
                                    <td><?php echo $auteurs; ?></td></tr>
                                <?php } ?>
                                <!-- Date created -->
                                <?php if(item('Dublin Core', 'Date Created')): ?>
                                <tr><td class="label"><?php echo __('Jaar') ?></td>
                                    <td><?php echo item('Dublin Core', 'Date Created', array('delimiter' => ', ')); ?></td></tr>
                                <?php endif; ?>
                                <!-- Creatie plaats -->
                                <?php if(item('Item Type Metadata', 'Creatie plaats')): ?>
                                <tr><td class="label"><?php echo __('Plaats') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Creatie plaats', array('delimiter' => ', ')); ?></td></tr>
                                <?php endif; ?>
                                <!-- Uitgever -->
                                <?php if(item('Dublin Core', 'Publisher')): ?>
                                <tr><td class="label"><?php echo __('Publisher') ?></td>
                                    <td><?php echo item('Dublin Core', 'Publisher', array('delimiter' => '<br />')); ?>
                                    </td></tr>
                                <?php endif; ?>
                                <!-- Taal -->
                                <?php if(item('Dublin Core', 'Language')): ?>
                                <tr><td class="label"><?php echo __('Language') ?></td>
                                    <td><?php echo item('Dublin Core', 'Language', array('delimiter' => ', ')); ?>
                                    </td></tr>
                                <?php endif; ?>
                                <!--Materiaal -->
                                <?php if(item('Item Type Metadata', 'Materiaal')): ?>
                                <tr><td class="label"><?php echo __('Materiaal') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Materiaal', array('delimiter' => ', ')); ?>
                                    </td></tr>
                                <?php endif; ?>
                                <!-- Temporal Coverage-->
                                <?php if(item('Item Type Metadata', 'Periode')): ?>
                                <tr><td class="label"><?php echo __('Periode') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Periode', array('delimiter' => ', ')); ?></td></tr>
                                <?php endif; ?>
                                <!-- Onderwerp plaats -->
                                <?php if(item('Item Type Metadata', 'Onderwerp plaats')): ?>
                                <tr><td class="label"><?php echo __('Plaats (Onderwerp)') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Onderwerp plaats', array('delimiter' => ', ')); ?></td></tr>
                                            <?php endif; ?>
                                <!-- Topstuk -->
                                <?php if(item('Item Type Metadata', 'Topstuk')): ?>
                                <tr><td class="label"><?php echo __('Topstuk') ?></td>
                                    <td><?php echo __(item('Item Type Metadata', 'Topstuk')); ?></td></tr>
                                <?php endif; ?>                                    

                                <!-- Links + erfgoedbibliotheek -->
                                <?php if(item('Item Type Metadata', 'Organisatie')): ?>
                                <tr><td class="label"><?php echo __('Erfgoedbibliotheek') ?></td>
                                    <td><a href="<?php echo item('Item Type Metadata', 'Link organisatie'); ?>">
                                    <?php echo item('Item Type Metadata', 'Organisatie'); ?></a></td></tr>
                                <?php endif; ?>
                                <!-- Catalogus -->
                                        <?php if(item('Item Type Metadata', 'Catalogus')): ?>
                                <tr><td class="label"><?php echo __('Catalogus') ?></td>
                                    <td>
                                        <?php
                                        $catArray = item('Item Type Metadata', 'Catalogus', array('all' => true));
                                        foreach($catArray as $cat){
                                        echo "<a href='".$cat."' target='_blank'>".$cat."</a><br>";
                                        }
                                        ?>
                                    </td>
                                        <?php endif; ?>
                                    <!--digitale kopie -->
                                        <?php if(item('Item Type Metadata', 'Object instelling')): ?>
                                <tr><td class="label"><?php echo __('Digitale kopie') ?></td>
                                    <td>
                                        <?php
                                        $objArray = item('Item Type Metadata', 'Object instelling', array('all' => true));
                                        foreach($objArray as $obj){
                                        echo "<a href='".$obj."' target='_blank'>".$obj."</a><br>";
                                        }?>
                                    </td>
                                    <?php endif; ?>
                                    <!-- Zie ook -->
                                <?php if(item('Item Type Metadata', 'Zie ook')): ?>
                                <tr><td class="label"><?php echo __('Zie ook') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Zie ook', array('delimiter' => ', ')); ?>
                                    </td></tr>
<?php endif; ?>
                                <!-- Literatuur -->
                                <?php if(item('Item Type Metadata', 'Literatuur')): ?>
                                <tr><td class="label"><?php echo __('Literatuurverwijzing') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Literatuur', array('delimiter' => ', ')); ?>
                                    </td></tr>
<?php endif; ?>
                                <!-- STCV-record, Abraham-record  -->
                                <?php if(item('Item Type Metadata', 'Extern systeem')): ?>
                                <tr><td class="label"><?php echo __('Referentiedatabank') ?></td>
                                    <td><a href="<?php echo item('Item Type Metadata', 'Extern systeem'); ?>"><?php echo item('Item Type Metadata', 'Extern systeem'); ?></a>
                                    </td></tr>
<?php endif; ?>
                                <!-- Toelichting -->
                                <?php if(item('Item Type Metadata', 'Toelichting')): ?>
                                <tr><td class="label"><?php echo __('Toelichting') ?></td>
                                    <td><?php echo item('Item Type Metadata', 'Toelichting', array('delimiter' => ', ')); ?>
                                    </td></tr>
<?php endif; ?>
                                <!-- Ander exemplaar -->
                                <?php if(item('Item Type Metadata', 'Ander exemplaar')): ?>
                                <tr><td class="label"><?php echo __('Ander exemplaar') ?></td>
                                    <td><a href="<?php echo item('Item Type Metadata', 'Ander exemplaar'); ?>"><?php echo item('Item Type Metadata', 'Ander exemplaar'); ?></a>
                                    </td></tr>
<?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tentoonstellingen -->
                    <?php $exhibits = Libis_link_to_related_exhibits_home(get_current_item());
                    if ($exhibits):
                        echo $exhibits;
                    endif; ?>
                </div>
            </div>
            <!--
            <ul class="item-pagination navigation noprint">
                                <li id="previous-item" class="previous"><?php //echo link_to_previous_item("Vorige",get_current_item());  ?></li>
                        <li id="next-item" class="next"><?php //echo link_to_next_item("Volgende",get_current_item());  ?></li>
                         </ul>
            -->
        </div>
        <div class="clearfix">&nbsp;</div>

    </div>
</div>
<div id="linkMaker" style="display:none;">
<?php echo digitool_get_image_from_file($_POST['pid']); ?>
</div>
<div id="linkResult" style="display:none;"></div>

<!-- HIDDEN / POP-UP DIV -->
    <?php if(sizeof($objArray)>1): ?>
<div id="pop-up">
    <?php
    echo "<ul>";
    $i = 1;
    foreach($objArray as $obj){
    echo "<li><a href='".$obj."' target='_blank'>Link ".$i."</a></li>";
    $i++;
    }
    echo "</ul>";
    ?>
</div>
<?php endif;?>
</div>

<script type="text/javascript">
    var addthis_config = {
        data_ga_property: 'UA-33179353-2',
        data_ga_social: true
    };

    //fixes height problems with tags
    jQuery(window).load(function () {
        if (jQuery('.extra-right').height() > jQuery('.extra-left').height()) {
            jQuery('.extra-left').height(jQuery('.extra-right').height());
        }
    });



    jQuery(document).ready(function ($) {
//543
        //put a click event on al the tag/links
        jQuery(".lightLink").each(function () {
            var newPid = jQuery(this).attr("alt");
            var link = jQuery(this);

            jQuery('#linkResult').load(window.location.pathname + ' #linkMaker', {pid: newPid}, function (data) {
                //link.attr('href',jQuery('#linkResult').html());
                //alert(jQuery('#linkResult').text());
                link.attr('href', jQuery('#linkResult').text());
                link.attr('rel', 'lightbox[pages]');
            });
        });

        jQuery('a.raad-pop').hover(function (event) {
            event.preventDefault();
            var pTop = jQuery(this).offset().top + 52;
            var pLeft = jQuery(this).offset().left;
            jQuery('div#pop-up').show()
                    .css('top', pTop)
                    .css('left', pLeft)
                    .appendTo('body');
        }, function () {
            ;

        });

        //if there are links to digital versions create a pop-up
        if (jQuery('#pop-up').length != 0) {
            jQuery("#pop-up").hover(
                    function () {
                        jQuery(this).show();
                    },
                    function () {
                        jQuery(this).hide()
                    });
        }
        //lightbox.start(jQuery(this));
        //jQuery('.imagecache-linked').removeAttr('href');

    });

</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4de3a8704c741536"></script>
<?php endif; ?>
<?php if(item_has_type("Nieuwsbericht")): ?>
<div class="clearfix"></div>

<div id="style_two">
    <div id="wrapper" class="cf">
        <div id="container">
            <div id="content">
                <div id="main" class="padding-left-20 padding-right-20">

                    <h2>
                        <?php
                        echo item('Dublin Core', 'Title');
                        if(item('Dublin Core', 'Alternative Title'))
                        echo " (".item('Dublin Core', 'Alternative Title').")";
                        ?>
                    </h2>


                    <div style="float:left;padding:0 10px 5px 0;"><?php
                        if(item_has_thumbnail()){
                        echo item_thumbnail();
                        }
                        ?>
                    </div>
                    <p>
                        <!-- Verantwoording -->

                    </p>
                </div>
            </div>
        </div>

        <div id="sidebar">
            <?php
            $items = get_items(array('recent' => 'true', 'type' => '15'), 1);
            if($items):
           
            echo "<h1>".link_to_item(item('Dublin Core', 'Title', array(), $items[0]), array(), 'show', $items[0])."</h1>";
            echo "<p>".digitool_get_thumb($items[0], true, false, 140)."<p>";
            echo "<p>".item("Item Type Metadata", "Verantwoording", array('snippet' => '300'), $items[0])."</p>";
            echo "<h3>".link_to_item('Lees meer', array('class' => 'more'), 'show', $items[0])."</h3>";
            endif;
            ?>

        </div>

        <div class="clearfix">&nbsp;</div>
    </div>
</div>

</div>
<?php endif; ?>
<?php foot(); ?>