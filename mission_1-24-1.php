<?php
    $str = "Hello i" . PHP_EOL;
    $filename="mission_1-24.txt";
    $fp = fopen($filename,"a");//wだと新規aだと追記
    fwrite($fp,$str);
    fclose($fp);
    echo "書き込み成功！";
?>