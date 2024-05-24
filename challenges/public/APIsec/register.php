<?php
include("utils/header.php");
session_start();
include("utils/dataRequest.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ユーザー名、メールアドレス、パスワードを取得
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // POSTデータを配列に
    $data = array(
        'username' => $username,
        'email' => $email,
        'password' => $password
    );

    $usersAPI = new UsersAPI();
    $responsePost = $usersAPI->sendPostRequest('',$data);
    
    // レスポンスに応じた処理
    if ($responsePost) {
        $_SESSION['message'] = "ユーザー登録が成功しました。ログインしてください。";
        header("Location: /APIsec/login.php");
        exit();
    } else {
        $message = "ユーザー登録に失敗しました。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header(); ?>
    <div class="form-container">
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
        <form action="" method="post">
            <label for="username">ユーザー名:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="email">メールアドレス:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">パスワード:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="新規登録">
        </form>
    </div>
</body>
</html>
