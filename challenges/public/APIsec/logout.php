<?php
include("utils/header.php");
// セッションを開始
session_start();
// セッション変数を全て削除
$_SESSION = array();
// セッションを切断するにはセッションクッキーも削除する。
// Note: セッション情報だけでなくセッションを破壊する。
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// 最終的に、セッションを破壊する
session_destroy();
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>ログアウト</title>
</head>
<body>
<?php echo generate_header() ?>
    <div class="form-container">
        <h2>ログアウトしました。</h2>
        <a href="login.php">ログインページへ戻る</a>
    </div>
</body>
</html>
