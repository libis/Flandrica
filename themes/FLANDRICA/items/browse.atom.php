<?php 
//get items and exhibits
$items = get_items(array("recent"=>"true"),5);//TODO:welk type?
$exhibits = exhibit_builder_recent_exhibits(5);
$recents = array();

//get items and exhibits in one array
for($i=0;$i<=10;$i++):
    if($items[$i])
            $recents[]=$items[$i];
    if($exhibits[$i])
            $recents[]=$exhibits[$i];
endfor;
//var_dump($recents);
$atom = new AtomExtend($recents);
echo $atom->getFeed();

?>