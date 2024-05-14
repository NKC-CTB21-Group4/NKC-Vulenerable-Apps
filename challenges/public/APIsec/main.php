<?php
include("utils/diaryDataExcerpt.php");

// ページ番号を取得
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// 1ページあたりの日記数
$diariesPerPage = 10;

// 日記の開始位置
$start = ($page - 1) * $diariesPerPage;

/**
 * JSONファイルから日記データを読み込み、日記の総数を返す関数。
 *
 * @param string $jsonFilePath JSONファイルのパス
 * @return int 日記の総数
 */
function countDiaries($jsonFilePath) {
    // JSONファイルを読み込む
    if (!file_exists($jsonFilePath)) {
        return 0;
    }

    $jsonData = file_get_contents($jsonFilePath);
    $diaries = json_decode($jsonData, true);

    // データの有無を確認
    if (empty($diaries)) {
        return 0;
    }

    $count = 0;
    foreach ($diaries as $diary) {
        if (!$diary['isPublic'] || $diary['isDeleted']) {
            continue; // 非公開または削除されたエントリーはカウントしない
        }
        $count++;
    }

    return $count;
}
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>日記サイト</title>
</head>
<body>
    <header>
        <h1>日記サイト</h1>
        <nav>
            <a href="register.php">新規登録</a> | <a href="login.php">ログイン</a>
        </nav>
    </header>
    <div id="main-content">
        <h2>News Update</h2>
        <p>ここに最新のニュースやアップデートを表示します。</p>
        <h2>公開日記</h2>
        <div class="border">
        <?php
        // 日記のタイトルを表示
        displayDiariesExcerpt('json/diarydata.json', 'title', $start, $diariesPerPage);
        ?>
        </div>
        <nav>
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">前へ</a>
            <?php endif; ?>
            <?php if ($page * $diariesPerPage < countDiaries('json/diarydata.json')): ?>
                <a href="?page=<?php echo $page + 1; ?>">次へ</a>
            <?php endif; ?>
        </nav>
    </div>
</body>
</html>
