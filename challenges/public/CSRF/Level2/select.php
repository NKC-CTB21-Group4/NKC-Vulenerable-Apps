<?php
session_start();

//ログイン処理結果表示
if (isset($_SESSION['login_error'])) {
    $loginerroralert = "<script type='text/javascript'>alert('ユーザー名またはパスワードが間違っています。');</script>";
    echo $loginerroralert;
    unset($_SESSION['login_error']); // フラグを削除する
}
//登録処理結果表示
if (isset($_SESSION['registration_success'])) {
    $regsuccessalert = "<script type='text/javascript'>alert('登録できました。');</script>";
    echo $regsuccessalert; // 変数名を修正
    unset($_SESSION['registration_success']); // フラグを削除する
} elseif (isset($_SESSION['registration_error'])) {
    $regerroralert = "<script type='text/javascript'>alert('登録できませんでした。');</script>";
    echo $regerroralert; // 変数名を修正
    unset($_SESSION['registration_error']);
}
// ログアウトがリクエストされた場合
if(isset($_POST['logout'])) {
    // セッションを破棄してログアウトする
    session_unset();
    session_destroy();
    header("Location: select.php"); // ログアウト後に最初の画面にリダイレクト
    exit();
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV.2ログイン画面</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>LV.2掲示板アプリ</h1>

        <!-- ログイン中のユーザー名がある場合、それを表示する -->
        <?php if (!empty($user_name)) { ?>
            <p>ようこそ、<?php echo $user_name; ?> さん</p>

            <!-- ログイン/登録後の投稿画面へのリンク -->
            <form action="post.php" method="get">
                <input type="submit" value="閲覧、投稿する">
            </form>

            <!-- ログアウトボタン -->
            <form action="" method="post">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        <?php } else { ?>
            <!-- ログインフォーム -->
            <h2>ログイン</h2>
            <form action="login.php" method="post">
                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="ログイン">
            </form>

            <!-- 新規登録フォーム -->
            <h2>新規登録</h2>
            <form action="register.php" method="post">
                <label for="new_username">ユーザー名:</label>
                <input type="text" id="new_username" name="new_username" required><br>
                <label for="new_password">パスワード:</label>
                <input type="password" id="new_password" name="new_password" required><br>
                <input type="submit" value="登録">
            </form>
        <?php } ?>
    </div>
</body>
</html>
