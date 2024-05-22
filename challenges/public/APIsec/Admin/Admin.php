<?php
ob_start(); // 出力バッファリングを開始

include("../utils/dataExcerpt.php");
include("../utils/parseJson.php");
include("../utils/header.php");

if (!is_admin()) {
    header("Location: /APIsec/main.php");
    exit();
}

$entry_NewsData = parseJson('../json/newsdata.json');
$entry_UserData = parseJson('../json/userdata.json');

// Newsのページ番号を取得
$newspage = isset($_GET['news-page']) ? (int)$_GET['news-page'] : 1;
if ($newspage < 1) {
    $newspage = 1;
}

// 1ページあたりのエントリー数
$PerPage = 5;

// エントリーの開始位置
$newsStart = ($newspage - 1) * $PerPage;

// エントリーの総数を取得
$totalNews = countEntry($entry_NewsData);

// 総ページ数を計算
$totalNewsPages = ceil($totalNews / $PerPage);
?>
<!DOCTYPE html>
<html>
<link href="../css/style.css" rel="stylesheet">
<link href="../css/admin.css" rel="stylesheet">
<head>
    <title>管理者ページ</title>
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <h2 class="Admin">管理者メインページ</h2>
        <div class="admin-links">
            <a href="viewUserList.php">ユーザー一覧表示ページ</a> |
            <a href="editNews.php">News編集ページ</a>
        </div>
        <h2 id="news-section" class="News">News編集</h2>
        <div class="border">
        <?php
        // Newsのタイトルを表示
        displayEntryExcerpt($entry_NewsData, 'title', $newsStart, $PerPage, 'editNews.php?id=');
        ?>
        </div>
        <nav class="pagination">
            <?php
            // Newsのページネーションリンクの表示
            for ($i = 1; $i <= $totalNewsPages; $i++) {
                if ($i == $newspage) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='admin.php?news-page={$i}#news-section'>{$i}</a> ";
                }
            }
            ?>
        </nav>
    </div>
</body>
</html>
