<?php
session_start();

// データベース接続
$db_path = "../sqlite/example.db";
$conn = new SQLite3($db_path);

// ログイン処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    // ユーザー名とパスワードを取得
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ユーザー名を使用してユーザーを検索
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    // ユーザーが見つかった場合
    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        // パスワードの検証
        if (password_verify($password, $row['password'])) {
            // ログイン成功：ユーザーの ID とユーザーネームをセッションに格納
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // ログイン後の処理（例えば、別のページにリダイレクト）
            header("Location: dashboard.php");
            exit();
        } else {
            // パスワードが間違っている場合の処理
            $_SESSION['login_error'] = true;
            header("Location: login.php");
            exit();
        }
    } else {
        // ユーザーが見つからない場合の処理
        $_SESSION['login_error'] = true;
        header("Location: login.php");
        exit();
    }
}

// 投稿処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['content'])) {
    // セッションからユーザーの ID とユーザーネームを取得
    $userid = $_SESSION['user_id'];
    $username = $_SESSION['user_name'];

    // 投稿のタイトルと内容を取得
    $title = $_POST['title'];
    $content = $_POST['content'];

    // 現在の日時を取得
    date_default_timezone_set('Asia/Tokyo');
    $created_at = date("Y-m-d H:i:s");

    // 投稿をデータベースに保存
    $stmt = $conn->prepare("INSERT INTO posts (title, content, created_at, user_id, username) VALUES (:title, :content, :created_at, :userid, :username)");
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':content', $content, SQLITE3_TEXT);
    $stmt->bindValue(':created_at', $created_at, SQLITE3_TEXT);
    $stmt->bindValue(':userid', $userid, SQLITE3_INTEGER);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    // 投稿が完了したら、投稿画面にリダイレクトする
    header("Location: post.php");
    exit();
}

// ログアウト処理
if(isset($_POST['logout'])) {
    // セッションを破棄してログアウトする
    session_unset();
    session_destroy();
    header("Location: select.php"); // ログアウト後に最初の画面にリダイレクト
    exit();
}
?>
