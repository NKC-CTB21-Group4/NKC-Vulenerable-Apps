<?php
include("utils/header.php");
// ダミーデータ
$diary = [
    'id' => 1,
    'title' => 'サンプルタイトル',
    'content' => 'サンプル内容'
];

// 実際には、APIからデータを取得して$diaryにセットする処理が必要です。
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>日記編集</title>
</head>
<body>
<?php echo generate_header() ?>
    <div class="form-container">
        <h2>日記を編集</h2>
        <form method="post" action="api/editDiary.php?id=<?php echo htmlspecialchars($diary['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <label for="title">タイトル:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($diary['title'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="content">内容:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($diary['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>

            <input type="submit" value="更新">
        </form>
        <form method="post" action="api/deleteDiary.php?id=<?php echo htmlspecialchars($diary['id'], ENT_QUOTES, 'UTF-8'); ?>" onsubmit="return confirm('この日記を削除してもよろしいですか？');">
            <input type="submit" value="削除" class="delete-button">
        </form>
    </div>
</body>
</html>
