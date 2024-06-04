<?php
include("../utils/header.php");
include("../utils/dataRequest.php");

$message = '';
$userId = 2;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newsId = isset($_POST['newsId']) ? (int)$_POST['newsId'] : 0;
    if ($newsId > 0) {
        // ダミーのuserIdを設定します。本来は認証システムなどから取得する必要があります。

        $data = [
            "newsId" => $newsId,
            "userId" => $userId
        ];

        $newsAPI = new NewsAPI();
        $responseDelete = $newsAPI->sendDeleteRequest('', $data);

        if ($responseDelete) {
            $message = "ニュース記事が削除されました。";
        } else {
            $message = "ニュース記事の削除に失敗しました: " . htmlspecialchars($responseDelete['error'], ENT_QUOTES, 'UTF-8');
        }
    } else {
        $message = "無効なニュースIDです。";
    }
} else {
    $message = "不正なリクエストです。";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ニュース記事削除</title>
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header(); ?>
    <div id="main-content">
        <h2>ニュース記事削除</h2>
        <div class="message">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <a href="Admin.php" class="back-button">ニュース一覧に戻る</a>
    </div>
</body>
</html>
