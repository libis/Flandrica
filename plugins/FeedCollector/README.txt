Authors:
http://www.libis.be/

License:
GPL

Description:
This plugin will enable you to place one or two rss feeds into your theme .

This is version 0.1 of this plugin and was tested on version 1.4.2 of OMEKA.


Instructions:
1. Upload and install the FeedCollector plugin into your plugins folder on the server, see: (Installing a Plugin).
2. Activate this from the Plugins page in your admin panel: /admin=>Settings=>Plugins.
3. Once activated, click Configure:
4. Here you enter 2 rss feeds and the number of entries you want to show. 

5. You can the view these feeds by putting the following line of code into your pages: 
	<?php echo(feedCollector_show()); ?>
6. This is structured like this (in case you want to syle it):
	<div class="feed-box">
		<h2>Feed Title</h2>
		<div class="news">
			<h6>Title</h6>
			<p>Content</p>
			<p><a class='more' href='link'>Lees meer</a></p>
		</div>
		...
	</div>	

Thanks for reading me!