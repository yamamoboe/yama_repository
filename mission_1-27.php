<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-27</title>
</head>
<body>
    <form action="" method="post">
        <input type="number" name="num" placeholder="数字を入力してください">
        <input type="submit" name="submit">
    </form>
    <?php
    $num = $_POST["num"];
    $send=0;//送信を押していない状態
    if($num != NULL){//これがないと、numが何で割っても(15で割っても)あまりが0となり、入力しなくてもFizzBuzzとなる
        if($num % 15 == 0) {
            $num = "FizzBuzz";
            $send=1;
        //送信を押したという状態
        } elseif ($num % 3 == 0) {
            $num = "Fizz";
            $send=1;
        } elseif ($num % 5 == 0) {
            $num = "Buzz";
            $send=1;
        } else {
            $send=1;
        }
    }
    if($send==1){
        echo "書き込み成功！<br>";//i=1のとき、つまり、送信したとき、書き込み成功となる。
        //ほんとは、「送信を押したら」書き込み成功と表示したい
    }
    if($num != NULL){ //実行しても送信しなければファイル追記しない=ファイルを新しく作らない
    $filename="mission_1-27.txt";
    $fp = fopen($filename,"a");//ファイル追記
    fwrite($fp,$num." ");
    fclose($fp);
    }
    if(file_exists($filename)){//filenameのファイルがあれば、中身を表示
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        echo $line . "<br>";
        }
    }
?>
</body>
</html>