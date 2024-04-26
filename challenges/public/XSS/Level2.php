<?php

$name = str_replace('<script>', '', $_GET['name']);
$name = str_replace('</script>', '', $name);
$name = trim($name); // 追加
$email = str_replace('<script>', '', $_GET['email']);
$email = str_replace('</script>', '', $email);
$email = trim($email); // 追加

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="style.css" type="text/css">
<head>
    <title>基本的な入力フォーム</title>
</head>
<body>
    <form action="./Level2.php" method="GET">
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

