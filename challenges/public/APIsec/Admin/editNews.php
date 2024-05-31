<?php
include("../utils/header.php");
include("../utils/dataRequest.php");
include("../utils/messageBox.php");

if (!is_admin()) {
    header("Location: /APIsec/main.php");
    exit();
}

// ニュースIDを取得
$newsId = $_GET['id'] ?? null;
$userId = 2; // ダミーのユーザーID、本来は認証システムから取得する必要があります。

// ニュースデータを取得
$newsAPI = new NewsAPI();
$newsResponseGet = $newsAPI->sendGetRequest($newsId);

// ニュースエントリーのデータがあるか確認
$newsEntry = $newsResponseGet["data"];

// 編集フォームの送信処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // 更新ボタンがクリックされた場合は、ニュースを更新する
    $title = $_POST['title'];
    $content = $_POST['content'];
    $isPublic = isset($_POST['isPublic']) ? (bool)$_POST['isPublic'] : false;

    // 更新データの作成
    $data = array(
        'userId' => $userId,
        'newsId' => $newsId,
        'title' => $title,
        'content' => $content,
        'isPublic' => $isPublic
    );

    $responseUpdate = $newsAPI->sendPutRequest('', $data);
    if ($responseUpdate) {
        // 更新成功時の処理（例えば、成功メッセージを表示）
        $_SESSION['message'] = "ニュースの編集に成功しました";
    } else {
        // 更新失敗時の処理（例えば、エラーメッセージを表示）
        $_SESSION['message'] = "ニュースの編集に失敗しました";
        var_dump($responseUpdate);
    }
}
?>

<!DOCTYPE html>
<html>
<link href="../css/style.css" rel="stylesheet">
<link href="../css/admin.css" rel="stylesheet">
<head>
    <title>ニュース編集</title>
</head>
<body>
    <?php echo generate_header() ?>
    <div class="form-container">
        <h2>ニュース編集</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="toast" id="toast">
                <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            </div>
            <?php ?>
        <?php endif; ?>
        <?php if ($newsEntry): ?>
            <form method="POST">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($newsEntry['title']); ?>" required>

                <label for="content">内容</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($newsEntry['content']); ?></textarea>

                <label for="isPublic">公開設定:</label>
                <select id="isPublic" name="isPublic" required>
                <option value="true">公開</option>
                <option value="false">非公開</option>
                </select>

                <input type="hidden" name="newsId" value="<?php echo htmlspecialchars($newsId); ?>">
                <input type="submit" name="update" value="更新" class="update-button">
            </form>

            <form method="POST" action="deleteNews.php" style="margin-top: 10px;">
                <input type="hidden" name="newsId" value="<?php echo htmlspecialchars($newsId); ?>">
                <input type="submit" name="delete" value="削除" class="delete-button">
            </form>
        <?php else: ?>
            <p>指定されたニュースエントリーが見つかりません。</p>
        <?php endif; ?>
    </div>
</body>
</html>
