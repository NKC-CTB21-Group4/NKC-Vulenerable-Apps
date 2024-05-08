<?php
require("../../utils/levelPageAccesCheck.php");
$path = $_SERVER['REQUEST_URI'];
if(!AccesCheck($path)){
    header("Location: /");
}
// サニタイジング処理
function sanitaizing($input){
    //配列にHTMLタグを格納
    $htmltags = ["script","img","div","a","td","table","style","svg","iframe"];

    foreach($htmltags as $tag){
        $input = preg_replace("/<".$tag.".*?>/i", "", $input);
        $input = preg_replace("/<\/".$tag.".*?>/i", "", $input);
    }
    return $input;
}

$name = sanitaizing($_GET['name']);
$email = sanitaizing($_GET['email']);
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
