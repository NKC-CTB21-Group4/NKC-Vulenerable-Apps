<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    // ユーザー名がセッションに保存されていない場合、エラーをセット
    
       
    // エラーメッセージを表示するページにリダイレクト
    header("Location: select.php");
    exit(); // リダイレクト後にスクリプトの実行を終了
}
if(isset($_POST['logout'])) {
    // セッションを破棄してログアウトする
    session_unset();
    session_destroy();
    header("Location: select.php"); // ログアウト後に最初の画面にリダイレクト
    exit();
}
// データベース接続
$db_path = "../sqlite/lv1example.db";
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
    <title>LV.1閲覧画面</title>
    <link rel="stylesheet" href="css/style.css"> <!-- スタイルシートのリンク -->
</head>
<body>

<section class="showsec">   
    <form class="show">
        <h2>過去の投稿</h2>
        <hr>
        <div class="post-container">
            <?php 
             foreach ($posts as $post) {
                $wrapped_content = nl2br(wordwrap($post['content'], 100, "\n", true));
                echo "<div>";
                echo "<h3>タイトル:{$post['title']}</h3>";
                echo "<h4>{$wrapped_content}</h4>";  // ここを変更しました
                echo "<p>投稿者: {$post['username']}(ID: {$post['user_id']})</p>";
                echo "<p>投稿日時: {$post['created_at']}</p>";
                // if ($_SESSION['user_id'] == $post['user_id']) {
                //     echo "<form action='delete_post.php' method='post'>";
                //     echo "<input type='hidden' name='post_id' value='{$post['id']}'>";
                //     echo "<input type='submit' name='btdelete' value='削除'>";
                //     echo "</form>";
                // }
                echo "</div>";
                echo "<hr>";
            }
            ?>
        </div>
    </form>
</section>
<form action="post.php" method="get">
    <input type="submit" value="投稿画面">
</form>
<form action="" method="post">
    <input type="submit" name="logout" value="ログアウト">
</form>

</body>
</html>
