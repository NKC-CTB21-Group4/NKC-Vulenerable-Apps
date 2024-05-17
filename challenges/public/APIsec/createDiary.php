<?php

?><!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>日記作成</title>
</head>
<body>
    <header>
        <h1>日記サイト</h1>
        <nav>
            <a href="register.php">新規登録</a> | <a href="login.php">ログイン</a>
        </nav>
    </header>
    <div class="form-container">
        <h2>新しい日記を作成</h2>
        <form method="post" action="api/createDiary.php">
            <label for="title">タイトル:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">内容:</label>
            <textarea id="content" name="content" required></textarea>

            <input type="submit" value="作成">
        </form>
    </div>
</body>
</html>
