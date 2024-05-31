<?php
include("../utils/header.php");
include("../utils/dataRequest.php");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id > 0) {
        // ダミーのuserIdを設定します。本来は認証システムなどから取得する必要があります。

        $data = [
            "id" => $id,
        ];

        $usersAPI = new UsersAPI();
        $responseDelete = $usersAPI->sendDeleteRequest('', $data);

        if ($responseDelete) {
            $message = "ユーザーが削除されました。";
        } else {
            $message = "ユーザーの削除に失敗しました: " . htmlspecialchars($responseDelete['error'], ENT_QUOTES, 'UTF-8');
        }
    } else {
        $message = "無効なユーザーIDです。";
    }
} else {
    $message = "不正なリクエストです。";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ユーザー削除</title>
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header(); ?>
    <div id="main-content">
        <h2>ユーザー削除</h2>
        <div class="message">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <a href="viewUserList.php" class="back-button">ユーザー一覧に戻る</a>
    </div>
</body>
</html>
