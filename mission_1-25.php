<?php
    $str = "Hello i" . PHP_EOL ;
    $ss= "HelloWld";
    $filename="mission_1-25.txt";
    $fp = fopen($filename,"w");
    fwrite($fp,$str.$ss);
    fclose($fp);
    echo "書き込み成功！";
?>