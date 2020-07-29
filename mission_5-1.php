<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

<?php 
	//データベース接続設定
	$dsn = 'mysql:dbname=************;host=*********'; //非公開
	$user = '*********';
	$password = '**********';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//yamadbというデータベースを作る
    $sqlydb = "CREATE TABLE IF NOT EXISTS yamadb" 
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "datetime TEXT,"
	. "pass TEXT"
	.");";
	$stmtydb = $pdo->query($sqlydb);
	
	//変数宣言
	if(isset($_POST["name"])){ 
		$name = $_POST["name"];//名前
		$comment = $_POST["comment"];//コメント
		$delno = $_POST["delete"];//削除番号
		$editno = $_POST["edit"]; //編集予定番号
		$pass_t = $_POST["passt"];
    	$pass_d = $_POST["passd"];
		$pass_e = $_POST["passe"];
		$editcheck = $_POST["editcheck"];//編集番号
		$editname = "";
		$editcomment = "";
	}else{
		$name = "";
		$comment = "";
		$delno = "";
		$editno = "";
		$pass_t = "";
		$pass_d = "";
		$pass_e = "";
		$editcheck = "";
		$editname = "";
		$editcomment = "";
	}
	$datetime = date("Y/m/d H:i:s");//日にち


	//書き込みモードおよび編集モード
	if( (empty($name)==0 || $name == "0") && (empty($comment)==0 || $comment == "0")){
		if(isset($_POST["passt"]) && empty($_POST["passt"])){
            echo "!パスワードが入力されていませんt!<br>";
        }
		//編集モード(編集番号があるとき)編集モードは書き込みの特別な時
		elseif(empty($editcheck)==0){
			$sqlydb = 'SELECT * FROM yamadb';
			$stmtydb = $pdo->query($sqlydb);
			$results = $stmtydb->fetchAll();
			foreach ($results as $row){
				if(($editcheck == $row['id'])){
					$sqlydb = 'UPDATE yamadb SET name=:name,comment=:comment,datetime=:datetime,pass=:pass WHERE id=:id';
    				$stmtydb = $pdo->prepare($sqlydb);
   					$stmtydb->bindParam(':name',$name,PDO::PARAM_STR);
				    $stmtydb->bindParam(':comment',$comment,PDO::PARAM_STR);
					$stmtydb -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
					$stmtydb -> bindParam(':pass', $_POST["passt"], PDO::PARAM_STR);
    				$stmtydb->bindParam(':id',$editcheck,PDO::PARAM_INT);
					$stmtydb->execute();
					echo $row['id']."番を編集しました<br>";
				break;
				}
			}
		}else{//書き込みモード
			$sqlydb = $pdo -> prepare("INSERT INTO yamadb (name, comment,datetime,pass)VALUES (:name,:comment,:datetime,:pass)");
    		$sqlydb -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
			$sqlydb -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
			$sqlydb -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
			$sqlydb -> bindParam(':pass', $_POST["passt"], PDO::PARAM_STR);
			$sqlydb -> execute();
			echo"書き込みをしました";
		}
	}
	if(isset($_POST["name"]) && empty($_POST["name"])&& empty($_POST["delete"]) && $name != "0" && empty($editno)){ //名前を書かずに送信ボタンを押したとき
        echo "!名前が入力されていません!<br>";
    }
    if(isset($_POST["comment"]) && empty($_POST["comment"]) && empty($_POST["delete"])&& $comment != "0" && empty($editno)){ //コメントを書かずに送信ボタンを押したとき
        echo "!コメントが入力されていません!<br>";
    }

	//削除モード
	if (empty($delno)==0){ //削除番号が入力されたとき
		if(isset($_POST["passd"]) && empty($pass_d)){
            echo "!パスワード入力されていませんd!<br>";
        }else{
			$d=0; //マーカー
			$sqlydb = 'SELECT * FROM yamadb';
			$stmtydb = $pdo->query($sqlydb);
			$results = $stmtydb->fetchAll();

			foreach ($results as $row){
				if(($delno == $row['id']) && ($pass_d == $row['pass'])){
    				$sqlydb = 'delete from yamadb where id=:id';
    				$stmtydb = $pdo->prepare($sqlydb);
    				$stmtydb->bindParam(':id', $delno, PDO::PARAM_INT);
					$stmtydb->execute();
					$d=1; //削除できたマーカー
				break;
				}elseif(($delno == $row['id']) && ($pass_d != $row['pass'])){
					$d=2; //パスワードが違ったマーカー
				break;
				}
			}	
			if($d==1){//もし正しく削除できたら
            	echo $row['id']."番を削除しました<br>";
        	}elseif($d==2){ //パスワードが違ったら
            	echo "!パスワードが違いますd!<br>";
        	}else{//削除するものがなかったら
            	echo "!". $delno."番はありませんd!<br>";
			}
		}
	}


	//編集ボタンを押したとき
	if (empty($editno)==0){//編集予定番号が入力されたとき
		if(isset($_POST["passe"]) && empty($pass_e)){
            echo "!パスワード入力されていませんe!<br>";
        }else{
			$e=0; //マーカー
			$sqlydb = 'SELECT * FROM yamadb';
			$stmtydb = $pdo->query($sqlydb);
			$results = $stmtydb->fetchAll();
			foreach ($results as $row){
				if(($editno == $row['id']) && ($pass_e == $row['pass'])){
					$eno = $row['id'];
					$e=1;
				break;
				}elseif(($editno == $row['id']) && ($pass_e != $row['pass'])){
					$e=2; //パスワードが違ったマーカー
				break;
				}
			}
			if($e==1){//もし編集するものがあれば
				echo $row['id']."番を編集中<br>";
				$editname = $row['name'];
				$editcomment = $row['comment'];
			}elseif($e==2){ //パスワードが違ったら
				echo "!パスワードが違いますe!<br>";
				$editno = NULL;
			}else{//編集するものが無かったら
				echo "!". $editno."番はありませんe!<br>";
				$editno = NULL;
			}
		}
	}
?>

<!--フォーム作成-->
<form action="" method="post">
		<?php echo"[書き込み]<br>";?>
        <input type="text" name="name" placeholder="名前"value = "<?php echo $editname; ?>">
        <input type="text" name="comment" placeholder="コメント"value = "<?php echo $editcomment; ?>"><br>
        <input type="text" name="passt" placeholder="パスワード"> 
        <input type="submit" name="submit"value="送信">
        <br>
		<?php echo"[削除]<br>";?>
        <input type="number" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="passd" placeholder="パスワード">
        <input type="submit" name="submit"value="削除">
        <br>
		<?php echo"[編集]<br>";?>
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="passe" placeholder="パスワード">
        <input type="submit" name="submit"value="編集">
        <input type="hidden" name="editcheck"placeholder="編集中"value = "<?php echo $editno; ?>">
</form>

<?php
	//データベースの中身をページに表示
	$sqlydb = 'SELECT * FROM yamadb';
	$stmtydb = $pdo->query($sqlydb);
	$results = $stmtydb->fetchAll();
	echo"<br><<山本の掲示板>><br>";
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['datetime'].'<br>';
		echo "　".$row['comment'].'<br>';
	echo "<hr>";
	}
?>

</body>
</html>