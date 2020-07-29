<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-4</title>
</head>
<body>
<?php
    $name = $_POST["name"];//名前
    $com = $_POST["comment"];//コメント
    $delno = $_POST["delete"]; //削除番号
    $editno = $_POST["edit"]; //編集番号
    $editcheck = $_POST["editcheck"];
    $date = date("Y/m/d H:i:s");//日にち
    $filename="mission_3-4.txt";
    $i=0;//行数カウント
    
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
    
    if((empty($name)==0 || $name == "0") && (empty($com)==0 || $com == "0") && empty($editcheck)){//書き込みモード
        //名前とコメントが送信されたとき
        //実行しても送信しなければファイル追記しない=ファイルを新しく作らない
        echo "コメントを受け付けました<br>";
        $fp = fopen($filename,"a");//ファイル追記
        fwrite($fp,$No."<>".$name."<>".$com."<>".$date.PHP_EOL);
        fclose($fp);
    }
    if((empty($name)==0 || $name == "0") && (empty($com)==0 || $com == "0") && empty($editcheck)==0){//編集モード
        for($m=0;$m<=$i-1;$m++){
            $edittext = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
            if ($edittext[0] == $editcheck){ //投稿されている投稿番号が、編集番号と一致したら、
                if($m==0){//もし一行目だったら
                    $fp = fopen($filename,"w");//ファイルを新規に作る
                    fwrite($fp,$edittext[0]."<>".$name."<>".$com."<>".$date." 編".PHP_EOL);//新しく入力されたものに編
                    fclose($fp);
                }else{//2行目以降なら
                    $fp = fopen($filename,"a");//ファイル追記
                    fwrite($fp,$edittext[0]."<>".$name."<>".$com."<>".$date." 編".PHP_EOL);//新しく入力されたものに編
                    fclose($fp);
                }

            }elseif($m==0){//上書きはじめ
                $fp = fopen($filename,"w");//ファイル新規につくる
                fwrite($fp,$lines[$m].PHP_EOL);
                fclose($fp);
            }else{
                $fp = fopen($filename,"a");//ファイル追記
                fwrite($fp,$lines[$m].PHP_EOL);
                fclose($fp);
            }
        }
        echo $editcheck."番を編集しました<br>";
        $edittext = NULL;//フォームのデフォルト値を消去
    }

    
    if(isset($_POST["name"]) && empty($_POST["name"])&& empty($_POST["delete"]) && $name != "0" && empty($editno)){ //名前を書かずに送信ボタンを押したとき
        echo "※名前が入力されていません。<br>";
    }
    if(isset($_POST["comment"]) && empty($_POST["comment"]) && empty($_POST["delete"])&& $com != "0" && empty($editno)){ //コメントを書かずに送信ボタンを押したとき
        echo "※コメントが入力されていません。<br>";
    }
        
    if (empty($_POST["delete"])==0){ //もし削除番号が入力されたとき
        $d=0;
        for($m=0;$m<=$i-1;$m++){
            $deltext = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
            if ($deltext[0] == $delno){ //最初の要素＝投稿されている投稿番号が、削除番号と一致したら、
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
        echo $delno."番を削除しました<br>";
        }else{//削除できなかったら
        echo "※". $delno."番はありません<br>";
        }
    }
    
    if (empty($_POST["edit"])==0){ //もし編集番号が入力されたとき
        $e=0;
        for($m=0;$m<=$i-1;$m++){
            $edittext = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
            if ($edittext[0] == $editno){ //最初の要素＝投稿されている投稿番号が、編集番号と一致したら、
                $e=1;
                break;
            }
        }
        if($e==1){//もし編集番号があったら
            echo $editno."番を編集中<br>";
        }else{//なかったら
            echo "※". $editno."番はありません<br>";
            $editno = NULL; //編集番号を消去
            $edittext = NULL; //フォームのデフォルト値を消去
        }
    }
?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value = "<?php echo $edittext[1];?>">
        <input type="text" name="comment" placeholder="コメント" value = "<?php echo $edittext[2];?>">
        <input type="submit" name="submit"value="送信">
        <br>
        <input type="number" name="delete" placeholder="削除対象番号">
        <input type="submit" name="submit"value="削除">
        <br>
        <input type="number" name="edit" placeholder="編集対象番号">
        <input type="submit" name="submit"value="編集">
        <input type="hidden" name="editcheck"placeholder="編集中"value = "<?php echo $editno; ?>"> 
        //typeをhiddenでフォームを隠す。numberにすると見える
    </form>
    
<?php      
    if(file_exists($filename)){//filenameのファイルの中身を表示
    echo "<br>＜ファイルの中身＞<br>";
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
        for($i=0;$i<=$No;$i++){
            $namecomdate = explode("<>",$lines[$i]);
            for($j=0;$j<=4;$j++){
                echo $namecomdate[$j]." ";
                }
            echo"<br>";
        }
    }
?>
</body>
</html>