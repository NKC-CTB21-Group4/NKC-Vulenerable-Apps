<?php
session_start();
if(isset($_POST['logout'])) {
    // セッションを破棄してログアウトする
    session_unset();
    session_destroy();
    header("Location: select.php"); // ログアウト後に最初の画面にリダイレクト
    exit();
}
// データベース接続
$db_path = "../sqlite/example.db";
$conn = new SQLite3($db_path);

// 投稿を取得するクエリ
$query = "SELECT * FROM posts ORDER BY created_at DESC"; // 新しい投稿から順に表示する例

// クエリを実行し、結果を取得
$result = $conn->query($query);

// 取得した投稿を配列に格納
$posts = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $posts[] = $row;
}

// データベース接続を閉じる
$conn->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿画面</title>
    <link rel="stylesheet" href="css/style.css"> <!-- スタイルシートのリンク -->
</head>
<body>

    
    

    <!-- 新規投稿フォーム -->
   
    <form action="submit_post.php" method="post" class="centered-form">
    <h2>新規投稿</h2>
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="content">内容:</label><br>
        <textarea id="content" name="content" rows="8" cols="100" required></textarea><br>
        <input type="submit" name="btpost" value="投稿する">
    </form>
    </section>
    <form action="show.php" method="get">
        <input type="submit" value="閲覧画面">
    </form>
    
    <form action="" method="post">
        <input type="submit" name="logout" value="ログアウト">
    </form>
    
</body>
</html>
