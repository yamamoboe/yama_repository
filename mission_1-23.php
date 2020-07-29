<?php
$items = array("Ken","Alice","Judy","BOSS","Bob");
foreach($items as $item){
    if($item=="BOSS"){
        echo "Good morning BOSS!<br>";
    }else{
        echo "Hello $item<br>";
    }
}
?>