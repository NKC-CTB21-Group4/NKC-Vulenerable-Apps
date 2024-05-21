<?php
include("utils/header.php");
// ユーザー名、メールアドレス、パスワードを取得
$username = htmlspecialchars($_POST['username']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

// ここでデータベースとの接続と認証を行います
// データベース接続と認証の詳細は、セキュリティとデータベースの設定によります
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>新規登録</title>
</head>
<body>
    <?php echo generate_header() ?>
    <div class="form-container">
    <form action="register.php" method="post">
        <label for="username">ユーザー名:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="email">メールアドレス:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="password">パスワード:</label><br>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="新規登録">
    </form>
    </div>
</body>
</html>
