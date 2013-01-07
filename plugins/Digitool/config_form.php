<div class="field">
	<label>CGI</label>
	<div class="inputs">
		<input type="text" name="cgi" size="60" value="<?php echo(get_option('digitool_cgi'));?>">	
	</div>
	<p class="explanation">Fill in the CGI link (used to find the images)</p>
</div>
<div class="field">
	<label>Proxy</label>
	<div class="inputs">
		<input type="text" name="proxy" size="60" value="<?php echo(get_option('digitool_proxy'));?>">	
	</div>
	<p class="explanation">Just enter the url of the proxy server (giving the portnumber doesn't hurt either) e.g. testproxy.cc.example.be:8080</p>
</div>
<div class="field">
	<label>Thumbnail link</label>
	<div class="inputs">
		<input type="text" name="thumb" size="60" value="<?php echo(get_option('digitool_thumb'));?>">	
	</div>
	<p class="explanation">Enter the link to get to the thumbnail p.e. http://example.com/get_pid?redirect&usagetype=THUMBNAIL&pid= (the pid bit is pretty essential)</p>
</div>
<div class="field">
	<label>View link</label>
	<div class="inputs">
		<input type="text" name="view" size="60" value="<?php echo(get_option('digitool_view'));?>">	
	</div>
	<p class="explanation">Enter the link to get to the view p.e. http://example.com/get_pid?redirect&usagetype=VIEW_MAIN,VIEW&pid=</p>
</div>
