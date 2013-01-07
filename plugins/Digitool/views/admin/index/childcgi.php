<?php
$xml = file_get_contents($_GET['child']);
	
//parse xml
$images = new SimpleXMLElement($xml);
print_r($images);
//create form

//print_r($images);
foreach ($images->children() as $image) {
	$arr = $image->attributes();   // returns an array
	$text .="<tr><td><img src='".$arr["thumbnail"]."'/></td>
        <td><Input type = 'Radio' Name ='thumb' value= '".$arr["thumbnail"]."'></td>"; 
	//print ("ID=".$arr["view"]);    // get the value of this attribute
	//check if there are children (complex object)
	//if($arr["children"]){
		//$text .="<td><a class='digi-child' href='#'>Complex object</a></td>";
	//}
	echo "</tr>";
}
if(empty($text))
	echo "No results where found";

$html .="<table>".$text."</table>";
echo $html;?>