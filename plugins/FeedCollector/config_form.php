<!-- The Radiobuttons (items or collections) -->


<!-- Text Area (where you adjust the AddThis button) -->
<div class="field">
	<label>Enter your rss field :</label>
	<div class="inputs">
		<input type="text" name="rss" size="60" value="<?php echo(get_option('feedCollector_rss'));?>">
	</div>
	<p class="explanation">For example http://feeds.bbci.co.uk/news/rss.xml</p> 
</div>
<div class="field">
	<label>Need a proxy?</label>
	<div class="inputs">
		<input type="text" name="proxy" size="60" value="<?php echo(get_option('feedCollector_proxy'));?>">	
	</div>
	<p class="explanation">Just enter the url of the proxy server (giving the portnumber doesn't hurt either) e.g. testproxy.cc.example.be:8080</p>
</div>
<div class="field">
	<label>How many entries do you want?</label>
	<div class="inputs">
		<input type="text" name="limit" size="5" value="<?php echo(get_option('feedCollector_limit'));?>">	
	</div>
	 <p class="explanation">This has to be a number, no funny stuff!</p> 
</div>
