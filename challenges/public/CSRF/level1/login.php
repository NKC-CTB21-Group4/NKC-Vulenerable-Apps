<?php
session_start();

// ログインがPOSTされたとき
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたユーザー名とパスワード
    $username = $_POST['username'];
    $password = $_POST['password'];

    // データベースに接続
    $db_path = "../sqlite/example.db";
    $conn = new SQLite3($db_path);

    // 入力されたユーザー名と一致するレコードを検索
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    // ユーザーが見つかった場合
    if ($row = $result->fetchArray()) {
        // パスワードが一致するかを確認
        if (password_verify($password, $row['password'])) {
            // ログイン成功
            header("Location: select.php"); 
            $_SESSION['user_name'] = $username;
            $_SESSION['user_id'] = $row['id'];// セッションにユーザー名を保存
             // ログイン後にリダイレクト
            exit();
        } else {
            // パスワードが一致しない場合の処理
            $_SESSION['login_error'] = true;
            header("Location: select.php"); 
            exit();
        }
    } else {
        // ユーザーが見つからない場合の処理
            $_SESSION['login_error'] = true;
            header("Location: select.php"); 
            exit();
    }

    // データベース接続を閉じる
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>ログインフォーム</h2>
    <form action="login.php" method="post">
        <label for="username">ユーザー名:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="ログイン">
    </form>
</body>
</html>
