<?php
session_start();

// セッションにユーザー名がセットされているか確認
if (!isset($_SESSION['username'])) {
    // ログインしていない場合はログインページにリダイレクト
    header("Location: login.php");
    exit();
}

// ログインしているユーザー名を取得
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Success</title>
</head>
<body>
    <h1>Login Successful!</h1>
    <p>Welcome, <?php echo $username; ?>!</p>
    <p>This is the protected content. Only logged-in users can see this.</p>
    <a href="level1.php?logout=true">Logout</a>
</body>
</html>
