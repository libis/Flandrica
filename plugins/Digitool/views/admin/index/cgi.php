<?php
//We need to use a proxy:
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml';
$headers[] = 'Connection: Keep-Alive';
$headers[] = 'Content-type: application/xml;charset=UTF-8';
$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0';

$ch = curl_init('http://libis-t-rosetta-1.libis.kuleuven.be/lias/cgi/find_pid?search=%25'.$_GET['search'].'%25&max_results=50');

//set options
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

curl_setopt($ch, CURLOPT_HEADER, 0);

//proxy
curl_setopt($ch, CURLOPT_PROXY,'icts-http-gw.cc.kuleuven.be:8080');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

//get data and close connection
$data = curl_exec($ch);

curl_close($ch);

//parse the resulting xml
$images = new SimpleXMLElement($data, LIBXML_NOCDATA);

$size = sizeof($images);

$i=0;$j=0;

foreach ($images->children() as $image) {
	$arr = $image->attributes();   // returns an array
	$i++;
	$j++;
	$text .="<p><img src='".$arr["thumbnail"]."'/><Input type = 'Radio' Name ='pid' value= '".$arr["pid"]."'>
      </p> "; 
	
	if($i==4 || $j == $size){
		$html .= "<div class='result' >".$text."</div>";
		$text="";$i=0;
	}
	
	//check if there are children (complex object)
	if($arr["children"]){
		//$text .="<td class='result-child'><button style='float:none;' class='digi-child' value= '".$arr["children"]."'>Get Children</button></td>";
	}
	
}
if(empty($html))
	echo "No results where found";

echo $html;?>
