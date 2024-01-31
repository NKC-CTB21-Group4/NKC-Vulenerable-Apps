<?php
require_once 'progressdata.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['issue'])) {
    $selectedIssue = $_GET['issue'];

    if (isset($progressLevels[$selectedIssue])) {
        $title = $selectedIssue;
        $progress = $progressLevels[$selectedIssue];
    } else {
        // 選択された脆弱性が存在しない場合のエラー処理
        $title = 'エラー';
        $progress = ['選択された脆弱性が存在しません'];
    }
} else {
    // GETリクエスト以外でアクセスされた場合のエラー処理
    $title = 'エラー';
    $progress = ['不正なアクセスです'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="progressdetail.css">
    <title>NKC Vulnerable Apps</title>
</head>
<body class="bodyprogressdetail">
    <div class="container">
        <h1>進捗状況</h1>
        
        <!-- 進捗表示 -->
        <?php if (isset($title, $progress)): ?>
            <h2>脆弱性: <?php echo $title; ?></h2>
            <?php foreach ($progress as $level => $result): ?>
                <div class="label <?php echo $result === 'x' ? 'level4' : 'level3'; ?>">
                    <?php echo $level; ?>
                </div>
            <?php endforeach; ?>
        <?php elseif (isset($title)): ?>
            <p class="error">エラー: 選択された脆弱性が存在しません</p>
        <?php endif; ?>

        <!-- 戻るボタン -->
        <form method="post" action="progress.php">
            <button class="btn_back" type="submit">戻る</button>
        </form>
    </div>
</body>
</html>
