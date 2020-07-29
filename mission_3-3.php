<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-3</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前">
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit"value="送信">
        <br>
        <input type="number" name="delete" placeholder="削除対象番号">
        <input type="submit" name="submit"value="削除">
    </form>
    
    <?php
    $name = $_POST["name"];//名前
    $com = $_POST["comment"];//コメント
    $del = $_POST["delete"]; //削除番号
    $date = date("Y年m/d H:i:s");//日にち
    $filename="mission_3-3.txt";
    $i=0;
    
    //投稿番号$Noの計算
    if(file_exists($filename)){//filenameのファイルがあれば
        $lines = file($filename,FILE_IGNORE_NEW_LINES);//コメントを一行ずつ配列linesに代入
        foreach($lines as $line){ //コメントの数=配列の要素数
        $i++;                    //コメントの数をカウント
        }
        $namecomdate = explode("<>",$lines[$i-1]);//コメントの数-1(配列の要素番号は0からだから)の要素(最後のコメント)を<>で区切る
        $No=$namecomdate[0]+1;  //その投稿番号+1が次の投稿番号
    }else{
        $No=1;//投稿番号の初期値(ファイルがないとき)は1
    }
    
    if((empty($name)==0 || $name == "0") && (empty($com)==0 || $com == "0")){
        //名前とコメントが送信されたとき
        //実行しても送信しなければファイル追記しない=ファイルを新しく作らない
        echo "コメントを受け付けました<br>";
        $fp = fopen($filename,"a");//ファイル追記
        fwrite($fp,$No."<>".$name."<>".$com."<>".$date.PHP_EOL);
        fclose($fp);
    }
    
    if(isset($_POST["name"]) && empty($_POST["name"])&& empty($_POST["delete"]) && $name != "0"){ //名前を書かずに送信ボタンを押したとき
        echo "※名前が入力されていません。<br>";
    }
    if(isset($_POST["comment"]) && empty($_POST["comment"]) && empty($_POST["delete"])&& $com != "0"){ //コメントを書かずに送信ボタンを押したとき
        echo "※コメントが入力されていません。<br>";
    }
        
    if (empty($_POST["delete"])==0){ //もし削除番号が入力されたとき
        $d=0;
        for($m=0;$m<=$i-1;$m++){
            $namecomdate = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
            if ($namecomdate[0] == $del){ //最初の要素＝投稿されている投稿番号が、削除番号と一致したら、
                 //何もしない＝追記しない
                 $d=1; //削除したマーカー
                if($m==0){//最初の行を消すのなら
                    $f=1;//最初の行を消したときのマーカーをつける
                }
            }elseif($m==0 || $f==1){//上書きはじめ、または最初の行を消したとき($f=1のとき)
                $fp = fopen($filename,"w");//ファイル新規につくる
                fwrite($fp,$lines[$m].PHP_EOL);
                fclose($fp);
                $f=0;//最初の行を消し終わりましたのマーカー
            }else{
                $fp = fopen($filename,"a");//ファイル追記
                fwrite($fp,$lines[$m].PHP_EOL);
                fclose($fp);
            }
        }
        if($d==1){//もし正しく削除できたら
        echo $del."番を削除しました<br>";
        }else{//削除できなかったら
        echo "※". $del."番はありません<br>";
        }
    }
        
    if(file_exists($filename)){//filenameのファイルがあれば、中身を表示
    echo "<br>＜ファイルの中身＞<br>";
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
        for($i=0;$i<=$No;$i++){
            $namecomdate = explode("<>",$lines[$i]);
            for($j=0;$j<=4;$j++){
                echo $namecomdate[$j];
                }
            echo"<br>";
        }
    }
?>
</body>
</html>