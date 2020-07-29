<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-20</title>
</head>
<body>
<form  action="" method="post">
	<input type="text" name="comment">
	<input type="submit" name="submit">
</form>
    <?php
        $str = $_POST["comment"];
        echo $str;
    ?>
</body>
</html>