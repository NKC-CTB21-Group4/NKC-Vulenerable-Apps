<?php
include("../utils/header.php");
include("../utils/dataRequest.php");
include("../utils/messageBox.php");

// 仮定のuserId
$userId = 1;

// フォームデータの受け取りと処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $isPublic = ($_POST['isPublic'] === "true") ? true : false;

    // APIに送信するデータの作成
    $data = array(
        'userId' => $userId,
        'title' => $title,
        'content' => $content,
        'isPublic' => $isPublic
    );

    $newsAPI = new NewsAPI();
    $responsePost = $newsAPI->sendPostRequest('', $data);
    // 結果の表示
    if ($responsePost) {
        $_SESSION['message'] = "ニュースが正常に作成されました。";
    } else {
        $_SESSION['message'] = "ニュースが作成されませんでした";
    }
}
?>

<!DOCTYPE html>
<html>
<link href="../css/style.css" rel="stylesheet">
<head>
    <title>ニュース作成</title>
</head>
<body>
<?php echo generate_header() ?>
    <div class="form-container news-form-container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="toast" id="toast">
                <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            </div>
        <?php endif; ?>
        <h2>新しいニュースを作成</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="title">タイトル:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">内容:</label>
            <textarea id="content" name="content" required></textarea>

            <label for="isPublic">公開設定:</label>
            <select id="isPublic" name="isPublic" required>
                <option value="true">公開</option>
                <option value="false">非公開</option>
            </select>

            <!-- 仮定のuserIdをhiddenで渡す -->
            <input type="hidden" name="userId" value="<?php echo $userId; ?>">

            <input type="submit" value="作成">
        </form>
    </div>
</body>
</html>
