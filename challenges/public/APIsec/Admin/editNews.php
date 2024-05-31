<?php
include("../utils/header.php");
include("../utils/dataRequest.php");

if (!is_admin()) {
    header("Location: /APIsec/main.php");
    exit();
}

// ニュースIDを取得
$newsId = $_GET['id'] ?? null;

// ニュースデータを取得
$newsAPI = new NewsAPI();
$newsResponseGet = $newsAPI->sendGetRequest($newsId);

// ニュースエントリーのデータがあるか確認
$newsEntry = $newsResponseGet["data"];

// 編集フォームの送信処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // 更新ボタンがクリックされた場合は、ニュースを更新する
        $title = $_POST['title'];
        $content = $_POST['content'];

        // 更新データの作成
        $data = array(
            'title' => $title,
            'content' => $content
        );

        $responseUpdate = $newsAPI->sendPutRequest($newsId, $data);
        if ($responseUpdate) {
            // 更新成功時の処理（例えば、成功メッセージを表示）
            echo "ニュースが更新されました。";
        } else {
            // 更新失敗時の処理（例えば、エラーメッセージを表示）
            echo "ニュースの更新中にエラーが発生しました。";
            var_dump($responseUpdate);
        }
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
        <?php if ($newsEntry): ?>
            <form method="POST" action="deleteNews.php">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($newsEntry['title']); ?>" required>

                <label for="content">内容</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($newsEntry['content']); ?></textarea>

                <input type="submit" name="update" value="更新" class="update-button">
                <input type="hidden" name="newsId" value="<?php echo htmlspecialchars($newsId); ?>">
                <input type="submit" name="delete" value="削除" class="delete-button">
            </form>
        <?php else: ?>
            <p>指定されたニュースエントリーが見つかりません。</p>
        <?php endif; ?>
    </div>
</body>
</html>
