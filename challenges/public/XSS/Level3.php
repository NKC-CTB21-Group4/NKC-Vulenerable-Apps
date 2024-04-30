<?php
// サニタイジング処理
$name = preg_replace('/<script.*?\/script.*>/', '', $_GET["name"]); // JavaScriptコードを削除
$name = strip_tags($name,['a']); // <a>タグ以外のHTMLタグを削除
$name = trim($name);

$email = preg_replace('/<script.*?\/script.*>/', '', $_GET["email"]); // JavaScriptコードを削除
$email = strip_tags($email, ['a']); // <a>タグ以外のHTMLタグを削除
$email = trim($email);
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="style.css" type="text/css">
<head>
    <title>基本的な入力フォーム</title>
</head>
<body>
    <form action="./Level3.php" method="GET">
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
