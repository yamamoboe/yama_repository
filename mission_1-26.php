<?php
$str1 = "Hello World" . PHP_EOL;
$str2 = "こんにちは" . PHP_EOL;
$str3 = "こんばんは" . PHP_EOL;
$filename="mission_1-24.txt";
$fp = fopen($filename,"w");
fwrite($fp,$str1.$str2.$str3);
fclose($fp);
echo "書き込み成功！<br>";

if(file_exists($filename)){
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        echo $line . "<br>";
    }
}
?>