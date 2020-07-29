<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-1</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit"value="送信">
    </form>
    <?php
    $com = $_POST["comment"];
    if($com != NULL){
        echo "$com を受け付けました<br>";
    }
    ?>
</body>
</html>