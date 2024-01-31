<?php
$progressLevels = [
    'インジェクション' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    '認証の不備' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    '情報の露呈' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'XXE' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'アクセス制御の不備' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'XSS' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'デシリアライゼーション' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'コンポーネント' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'ロギング' => ['レベル1', 'レベル2', 'レベル3', 'レベル4'],
    'Text-text' => ['レベル1', 'レベル2', 'レベル3', 'レベル4']
];

$chunkedVulnerabilities = array_chunk(array_keys($progressLevels), 5); // 5つずつの要素に分割

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['security_issue'])) {
        $selectedIssue = $_POST['security_issue'];

        // ボタンがクリックされた脆弱性の情報を取得
        if (isset($progressLevels[$selectedIssue])) {
            header("Location: progressdetail.php?issue=$selectedIssue");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="progress.css">
    <title>NKC Vulnerable Apps</title>
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        <h1>進捗状況</h1>
        
        <!-- フォーム -->
        <form method="post" action="">
            <?php foreach ($chunkedVulnerabilities as $row): ?>
                <div class="button-row">
                    <div class="button-group">
                        <?php foreach ($row as $issue): ?>
                            <?php if (isset($progressLevels[$issue])): ?>
                                <button type="submit" name="security_issue" value="<?php echo $issue; ?>"><?php echo $issue; ?></button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>

        <!-- 戻るボタン -->
        <form method="post" action="/">
            <button class="btn_back" type="submit">戻る</button>
        </form>
    </div>
</body>
</html>
