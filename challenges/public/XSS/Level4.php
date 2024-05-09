<?php
require("../../utils/levelPageAccesCheck.php");

if(AccessBlock())header("Location: /");

// サニタイジング処理
$name = preg_replace('/<(.|\n)*>/i', '', $_GET["name"]); // HTMLタグを削除
$name = preg_replace('/<script.*?\/script.*>/i', '', $name); // JavaScriptコードを削除
$name = trim($name);

$email = preg_replace('/<(.|\n)*?>/i', '', $_GET["email"]); // HTMLタグを削除
$email = preg_replace('/<script.*?\/script.*>/i', '', $email); // JavaScriptコードを削除
$email = trim($email);
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="style.css" type="text/css">
<head>
    <title>基本的な入力フォーム</title>
</head>
<body>
    <form action="./Level4.php" method="GET">
        <label for="name">名前:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="email">メール:</label><br>
        <input type="email" id="email" name="email"><br>
        <input type="submit" value="送信">
    </form>
    <h2><?php echo $name?></h2>
    <h2><?php echo $email?></h2>
</body>
</html>
