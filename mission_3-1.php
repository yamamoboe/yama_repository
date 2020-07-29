<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-1</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前">
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit"value="送信">
    </form>
    
    <?php
    $name = $_POST["name"];
    $com = $_POST["comment"];
    $date = date("Y年m/d/ H:i:s");
    $filename="mission_3-1.txt";
    if(file_exists($filename)){//filenameのファイルがあれば
        $lines = file($filename,FILE_IGNORE_NEW_LINES);//一行ずつ配列に代入
        foreach($lines as $line){ //コメントの数=配列の要素数
        $No++;
        }
        $No=$No+1;//その要素数の次の数
    }else{
        $No=1;//初期値は1
    }
    if((empty($name)==0 || $name == "0") && (empty($com)==0 || $com == "0")){
        //初めて実行しても送信しなければファイル追記しない=ファイルを新しく作らない
        echo "コメントを受け付けました<br><br>";
        $fp = fopen($filename,"a");//ファイル追記
        fwrite($fp,$No."<>".$name."<>".$com."<>".$date.PHP_EOL);
        fclose($fp);
        
    }
    if(isset($_POST["name"]) && empty($_POST["name"])){ //名前を書かずに送信ボタンを押したとき
        echo "※名前が入力されていません。<br>";
    }
    if(isset($_POST["comment"]) && empty($_POST["comment"])){ //コメントを書かずに送信ボタンを押したとき
        echo "※コメントが入力されていません。<br><br>";
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