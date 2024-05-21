<?php
include("utils/header.php");
// ユーザー名、メールアドレス、パスワードを取得
$username = htmlspecialchars($_POST['username']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);


?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>ログイン</title>
</head>
<body>
<?php echo generate_header() ?>
    <div class="form-container">
    <form action="login.php" method="post">
        <label for="email">メールアドレス:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="password">パスワード:</label><br>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="ログイン">
    </form>
    </div>
</body>
</html>
