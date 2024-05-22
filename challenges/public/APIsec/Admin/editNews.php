<?php
include("../utils/dataExcerpt.php");
include("../utils/parseJson.php");
include("../utils/header.php");

if (!is_admin()) {
    header("Location: /APIsec/main.php");
    exit();
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
            <form method="POST">
                <label for="title">タイトル</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($newsEntry['title']); ?>" required>

                <label for="content">内容</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($newsEntry['content']); ?></textarea>

                <input type="submit" value="更新">
                <input type="submit" name="delete" value="削除" class="delete-button">
            </form>
        <?php else: ?>
            <p>指定されたニュースエントリーが見つかりません。</p>
        <?php endif; ?>
    </div>
</body>
</html>