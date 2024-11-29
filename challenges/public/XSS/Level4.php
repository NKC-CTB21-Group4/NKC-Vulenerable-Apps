<?php
require("../../utils/levelPageAccessBlock.php");
require_once "/app/public/api/add_request_to_file.php";

// サニタイジング処理
$name = preg_replace('/<(.|\n)*>/i', '', $_GET["name"]); // HTMLタグを削除
$name = preg_replace('/<script.*?\/script.*>/i', '', $name); // JavaScriptコードを削除
$name = trim($name);

$email = preg_replace('/<(.|\n)*?>/i', '', $_GET["email"]); // HTMLタグを削除
$email = preg_replace('/<script.*?\/script.*>/i', '', $email); // JavaScriptコードを削除
$email = trim($email);

$PROBREM_NAME = "xss-4";

if($_SERVER['HTTP_USER_AGENT'] != "evaluator"){
    addToQueue($PROBREM_NAME,["code" => "http://localhost:8081" . $_SERVER['REQUEST_URI']]);
}
if(AccessBlock())header("Location: /");
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
