<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-2</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit"value="送信">
    </form>
    <?php
    $com = $_POST["comment"];
    $filename="mission_2-2.txt";
    if($com != NULL){//実行しても送信しなければファイル追記しない=ファイルを新しく作らない
        echo "$com を受け付けました<br><br>";
        if ($com == "完成！"){
            echo "おめでとう！<br><br>";
        }
        $fp = fopen($filename,"a");//ファイル追記
        fwrite($fp,$com.PHP_EOL);
        fclose($fp);
        
    }
    if(isset($_POST["comment"]) && $com==NULL){ //何も書かずに送信ボタンを押したとき
        echo "なにも入力されていません。<br><br>";
    }
    
    if(file_exists($filename)){//filenameのファイルがあれば、中身を表示
    echo "＜ファイルの中身＞<br>";
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        echo $line . "<br>";
        }
    }

    ?>
</body>
</html>