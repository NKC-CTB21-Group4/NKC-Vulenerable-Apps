<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV.1投稿画面</title>
    <link rel="stylesheet" href="css/style.css"> <!-- スタイルシートのリンク -->
</head>
<body>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="submit_post.php" method="post" class="centered-form">
        <h2>新規投稿</h2>
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" required maxlength="25"><br>
        <label for="content">内容:</label><br>
        <textarea id="content" name="content" rows="8" cols="100" required></textarea><br>
        <input type="submit" name="btpost" value="投稿する">
    </form>
    <form action="show.php" method="get">
        <input type="submit" value="閲覧画面">
    </form>
    <form action="submit_post.php" method="post">
        <input type="submit" name="logout" value="ログアウト">
    </form>
</body>
</html>
