<?php head(array('title' => html_escape(exhibit('title') . ' : '. exhibit_page('title')), 'bodyid'=>'exhibit','bodyclass'=>'show')); ?>
        <div class="clearfix"></div>
        <div id="breadcrumb">
            <ul>
                <li>
                    <a href="<?php echo html_escape(uri('')); ?>">Home</a>
                </li>
                 <li>
                     <a href="<?php echo html_escape(uri('exhibits/browse/'));?>"><?php echo __("Tours");?></a>
                </li>
                <li>
                     <a href="<?php echo html_escape(uri('exhibits/show/' . $exhibit['slug']));?>"><?php echo html_escape($exhibit['title']); ?></a>
                </li>
                <li>
                    <a href="<?php echo html_escape(uri('exhibits/show/' . $exhibit['slug'].'/' . $exhibitSection['slug']));?>"><?php echo html_escape($exhibitSection['title']); ?></a>
                </li>
                <li><?php echo html_escape(exhibit_page('title')); ?></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        <div id="style_four" class="two_columns">
            <div id="wrapper" class="cf">
                <div id="container">
                    <div id="content">
                        <article class="padding-left-20 padding-right-20 article">
                            <header>
                                <nav class="rondleidingNav right">
                                	<?php echo exhibit_builder_link_to_previous_exhibit_page('<img src="'.img("left_ss.png").'" title="goleft">'); ?>
    								<?php echo exhibit_builder_link_to_next_exhibit_page('<img src="'.img("right_ss.png").'" title="goright">'); ?>

                                </nav>
                                <div class="tentoonstellingsTitel"><?php echo link_to_exhibit(); ?></div>
                                <h1><?php echo exhibit_page('title'); ?></h1>
                            </header>
                         	<?php exhibit_builder_render_exhibit_page(); ?>
                        </article>
                    </div>
                </div>

                <div class="clearfix">&nbsp;</div>
            </div>
        </div>

    </div>




	<div id="exhibit-page-navigation">

	</div>

<?php foot(); ?>