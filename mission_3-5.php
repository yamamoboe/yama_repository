<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
<?php
    $name = $_POST["name"];//名前
    $com = $_POST["comment"];//コメント
    $delno = $_POST["delete"]; //削除番号
    $editno = $_POST["edit"]; //編集番号
    $pass_t = $_POST["passt"];
    $pass_d = $_POST["passd"];
    $pass_e = $_POST["passe"];
    $editcheck = $_POST["editcheck"];
    $date = date("Y/m/d H:i:s");//日にち
    $filename="mission_3-5.txt";
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
        if(isset($_POST["passt"]) && empty($_POST["passt"])){
            echo "!パスワードが入力されていませんt!<br>";
        }else{
        //名前とコメントが送信されたとき
        //実行しても送信しなければファイル追記しない=ファイルを新しく作らない
        echo "コメントを受け付けました<br>";
        $fp = fopen($filename,"a");//ファイル追記
        fwrite($fp,$No."<>".$name."<>".$com."<>".$date."<>".$pass_t."<>".PHP_EOL);
        fclose($fp);
        }
    }
    
    if((empty($name)==0 || $name == "0") && (empty($com)==0 || $com == "0") && empty($editcheck)==0 && empty($pass_t)==0){//編集モード2
        if(isset($_POST["passt"]) && empty($_POST["passt"])){
            echo "!パスワードが入力されていませんe!<br>";
        }else{
        for($m=0;$m<=$i-1;$m++){
            $edittext = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
            if ($edittext[0] == $editcheck){ //投稿されている投稿番号が、編集番号と一致したら、
                if($m==0){//もし一行目だったら
                    $fp = fopen($filename,"w");//ファイルを新規に作る
                    fwrite($fp,$edittext[0]."<>".$name."<>".$com."<>".$date."<>".$pass_t.PHP_EOL);//新しく入力されたものに編
                    fclose($fp);
                }else{//2行目以降なら
                    $fp = fopen($filename,"a");//ファイル追記
                    fwrite($fp,$edittext[0]."<>".$name."<>".$com."<>".$date."<>".$pass_t.PHP_EOL);//新しく入力されたものに編
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
    }
    
    if(isset($_POST["name"]) && empty($_POST["name"])&& empty($_POST["delete"]) && $name != "0" && empty($editno)){ //名前を書かずに送信ボタンを押したとき
        echo "!名前が入力されていません!<br>";
    }
    if(isset($_POST["comment"]) && empty($_POST["comment"]) && empty($_POST["delete"])&& $com != "0" && empty($editno)){ //コメントを書かずに送信ボタンを押したとき
        echo "!コメントが入力されていません!<br>";
    }
    

    //削除モード
    if (empty($_POST["delete"])==0 && empty($pass_d)==0){ //もし削除番号が入力されたとき
        if(isset($_POST["passd"]) && empty($_POST["passd"])){
            echo "!パスワード入力されていませんd!<br>";
        }else{
        $d=0;
        for($m=0;$m<=$i-1;$m++){
            $deltext = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
            if (($deltext[0] == $delno) && ($deltext[4] == $pass_d)){ //最初の要素＝投稿されている投稿番号が、削除番号と一致したら、
                 //何もしない＝追記しない
                $d=1; //削除したマーカー
                if($m==0){//最初の行を消すのなら
                    $f=1;//最初の行を消したときのマーカーをつける
                }
            }elseif(($deltext[0] == $delno) && ($deltext[4] != $pass_d)){
                $d=2;//パスワードが違ったマーカー
                if($m==0){//上書きはじめ、または最初の行を消したとき($f=1のとき)
                    $fp = fopen($filename,"w");//ファイル新規につくる
                    fwrite($fp,$lines[$m].PHP_EOL);
                    fclose($fp);
                }else{
                    $fp = fopen($filename,"a");//ファイル追記
                    fwrite($fp,$lines[$m].PHP_EOL);
                    fclose($fp);
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
        }elseif($d==2){
            echo "!パスワードが違います!<br>";
        }else{//削除できなかったら
            echo "!". $delno."番はありません!<br>";
        }
    }
    }
    
    //編集モード1
    if (empty($_POST["edit"])==0){ //もし編集番号が入力されたとき
        if(isset($_POST["passe"]) && empty($_POST["passe"])){
            echo "!パスワードが入力されていませんe!<br>";
        }else{
            $e=0;
            for($m=0;$m<=$i-1;$m++){
                $edittext = explode("<>",$lines[$m]); //配列linesを最初から代入し、それを<>で区切り、
                if ($edittext[0] == $editno && $edittext[4] == $pass_e){ //最初の要素＝投稿されている投稿番号が、編集番号と一致したら、
                    $e=1;//編集中モードマーカー
                    break; //forループから強制的に抜けるの意味
                }elseif($edittext[0] == $editno && $edittext[4] != $pass_e){
                    $e=2;//パスワードが違ったマーカー
                    break;
                }
            }
            if($e==1){//もし編集番号があったら
                echo $editno."番を編集中<br>";
            }elseif($e==2){
                echo "パスワードが違います!<br>";
                $editno = NULL; //編集番号を消去
                $edittext = NULL;
            }else{//なかったら
                echo "!". $editno."番はありません<br>";
                $editno = NULL; //編集番号を消去
            }
        }
    }
?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value = "<?php echo $edittext[1];?>"><br>
        <input type="text" name="comment" placeholder="コメント" value = "<?php echo $edittext[2];?>"><br>
        <input type="password" name="passt" placeholder="パスワード">
        <input type="submit" name="submit"value="送信">
        <br>
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="password" name="passd" placeholder="パスワード">
>
        <input type="submit" name="submit"value="削除">
        <br>
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="password" name="passe" placeholder="パスワード">
        <input type="submit" name="submit"value="編集">
        <input type="hidden" name="editcheck"placeholder="編集中"value = "<?php echo $editno; ?>">
    </form>
    
<?php      
    if(file_exists($filename)){//filenameのファイルの中身を表示
    echo "<br>＜ファイルの中身＞<br>";
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
        for($i=0;$i<=$No;$i++){
            $namecomdate = explode("<>",$lines[$i]);
            for($j=0;$j<=3;$j++){
                echo $namecomdate[$j]." ";
                }
            echo"<br>";
        }
    }
?>
</body>
</html>