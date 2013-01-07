<?php
// Toegevoegd door Sam
class cURL {
	var $headers;
	var $user_agent;
	var $compression;
	var $proxy;
	protected $_status;
	function cURL($compression='gzip') {
		$this->headers[] = 'Accept: text/html,application/xhtml+xml,application/xml';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/xml;charset=UTF-8';
		$this->user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0';
		$this->compression=$compression;
	}

	function setproxy($proxy) {
		$this->proxy = $proxy;
	}

	function setuserpw($userpw) {
		$this->userpw = $userpw;
	}

	function setport($port) {
		$this->port = $port;
	}
	function setuser_agent($user_agent) {
		$this->user_agent = $user_agent;
	}	

	function get($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ( isset($this->userpw) ) curl_setopt($process, CURLOPT_USERPWD, $this->userpw );
		if ( isset($this->port) ) curl_setopt($process, CURLOPT_PORT,$this->port);		
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);		
		if ($this->proxy) curl_setopt($process,CURLOPT_PROXYPORT, 8080); 
//		if ($this->proxy) curl_setopt($process, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 0);
		$return = curl_exec($process);
		$this->_status = curl_getinfo($process,CURLINFO_HTTP_CODE);

		curl_close($process);		
		return $return;
	}
	

	function getHttpStatus()   {
       return $this->_status;
   	} 

	function error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
}
?>
