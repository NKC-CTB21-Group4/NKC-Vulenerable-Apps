<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // データベース接続
    $db_path = "../sqlite/lv2example.db";
    $conn = new SQLite3($db_path);

    $random_id = mt_rand(100000, 999999); // 100000から999999の間のランダムな数
    // 入力されたユーザー名とパスワード
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // ユーザー登録
    $stmt = $conn->prepare("INSERT INTO users (id, username, password) VALUES (:id, :username, :password)");
    $stmt->bindValue(':id', $random_id, SQLITE3_INTEGER);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['registration_success'] = true;
    } else {
        $_SESSION['registration_error'] = true;
    }

    // データベース接続を閉じる
    $conn->close();

    // 登録結果に応じてアラートを表示してindex.phpにリダイレクト
    header("Location: select.php");
    exit();
}
?>
