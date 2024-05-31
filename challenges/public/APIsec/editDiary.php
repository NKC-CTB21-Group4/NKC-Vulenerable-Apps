<?php
include("utils/header.php");
include("utils/dataRequest.php");

// ダミーデータ（実際にはAPIからデータを取得する必要があります）
$diary = [
    'id' => 1,
    'title' => 'サンプルタイトル',
    'content' => 'サンプル内容'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Edit diary
    $diaryId = isset($_GET['id']) ? intval($_GET['id']) : null;
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';

    // ダミーのuserIdを設定します。本来は認証システムなどから取得する必要があります。
    $userId = 1; 
    $isPublic = false; // 必要に応じて公開フラグを設定

    $data = [
        "userId" => $userId,
        "diaryId" => $diaryId,
        "title" => $title,
        "content" => $content,
        "isPublic" => $isPublic
    ];

    $diaryAPI = new DiaryAPI();
    $responsePut = $diaryAPI -> sendPutRequest($data);

    if ($responsePut['success']) {
        echo "日記が更新されました。";
    } else {
        echo "日記の更新に失敗しました: " . htmlspecialchars($responsePut['error'], ENT_QUOTES, 'UTF-8');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete diary
    $diaryId = isset($_GET['id']) ? intval($_GET['id']) : null;

    // ダミーのuserIdを設定します。本来は認証システムなどから取得する必要があります。
    $userId = 1;

    $data = [
        "userId" => $userId,
        "diaryId" => $diaryId
    ];

    $diaryAPI = new DiaryAPI();
    $responseDelete = $diaryAPI -> sendDeleteRequest($data);

    if ($responseDelete['success']) {
        echo "日記が削除されました。";
    } else {
        echo "日記の削除に失敗しました: " . htmlspecialchars($responseDelete['error'], ENT_QUOTES, 'UTF-8');
    }
} else {
    echo "不正なリクエストです。";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>日記編集</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php echo generate_header() ?>
    <div class="form-container">
        <h2>日記を編集</h2>
        <form method="post" action="editDiary.php?id=<?php echo htmlspecialchars($diary['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="title">タイトル:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($diary['title'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="content">内容:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($diary['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <input type="submit" value="更新" class="update-button">
        </form>
        <form method="post" action="editDiary.php?id=<?php echo htmlspecialchars($diary['id'], ENT_QUOTES, 'UTF-8'); ?>" onsubmit="return confirm('この日記を削除してもよろしいですか？');">
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" value="削除" class="delete-button">
        </form>
    </div>
</body>
</html>
