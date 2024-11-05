<?php
include("utils/header.php");
session_start();
include("utils/dataRequest.php");
include("utils/messageBox.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // メールアドレスとパスワードを取得
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // APIエンドポイント
    $endpoint = 'api/users';

    // POSTデータを配列に
    $data = array(
        'email' => $email,
        'password' => $password
    );

    // リクエストを送信してレスポンスを取得
    $response = sendRequest($endpoint, $data);

    // レスポンスに応じた処理
    if ($response !== false) {
        $_SESSION['message'] = "ログインに成功しました。";
        header('Location: main.php'); // ダッシュボードなどのリダイレクト先に変更してください
        exit();
    } else {
        $_SESSION['message'] = "ログインに失敗しました。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header(); ?>
    <div class="form-container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="toast" id="toast">
                <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            </div>
            <?php ?>
        <?php endif; ?>
        <form action="" method="post">
            <label for="email">メールアドレス:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">パスワード:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="ログイン">
        </form>
    </div>
</body>
</html>
