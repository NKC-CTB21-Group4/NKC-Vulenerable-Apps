<?php
require("../../utils/levelPageAccessBlock.php");
require_once "/app/public/api/add_request_to_file.php";

$name = $_GET['name'];
$email = $_GET['email'];
$PROBREM_NAME = "xss-1";

if($_SERVER['HTTP_USER_AGENT'] != "evaluator"){
    addToQueue($PROBREM_NAME,$_SERVER['REQUEST_URI']);
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
    <form action="./Level1.php" method="GET">
        <label for="name">名前:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="email">メール:</label><br>
        <input type="email" id="email" name="email"><br>
        <input type="submit" value="送信">
    </form>
    <h2><?php echo $_GET['name']?></h2>
    <h2><?php echo $_GET['email']?></h2>
</body>
</html>
