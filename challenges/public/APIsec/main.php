<?php
include("utils/dataExcerpt.php");
include("utils/newsData.php");
include("utils/parseJson.php");

$entry_NewsData = parseJson('json/newsdata.json');
$entry_DiaryData = parseJson('json/diarydata.json');

// ニュースページ番号を取得
$newsPage = isset($_GET['newsPage']) ? (int)$_GET['newsPage'] : 1;
if ($newsPage < 1) {
    $newsPage = 1;
}

// 日記ページ番号を取得
$diaryPage = isset($_GET['diaryPage']) ? (int)$_GET['diaryPage'] : 1;
if ($diaryPage < 1) {
    $diaryPage = 1;
}

// 1ページあたりのエントリー数
$perPage = 5;

// ニュースの開始位置
$newsStart = ($newsPage - 1) * $perPage;

// 日記の開始位置
$diaryStart = ($diaryPage - 1) * $perPage;

// エントリーの総数を取得
$totalNews = countEntry($entry_NewsData);
$totalDiaries = countEntry($entry_DiaryData);

// 総ページ数を計算
$totalNewsPages = ceil($totalNews / $perPage);
$totalDiaryPages = ceil($totalDiaries / $perPage);
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
        <h2 class="News">News Update</h2>
        <div class="border">
        <?php
        displayEntryExcerpt($entry_NewsData, 'title', $newsStart, $perPage);    
        ?>
        </div>
        <nav class="pagination">
            <?php
            // ニュースページネーションリンクの表示
            for ($i = 1; $i <= $totalNewsPages; $i++) {
                if ($i == $newsPage) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='?newsPage={$i}&diaryPage={$diaryPage}'>{$i}</a> ";
                }
            }
            ?>
        </nav>
        <h2 class="ViewDiary">公開日記</h2>
        <div class="border">
        <?php
        // 日記のタイトルを表示
        displayEntryExcerpt($entry_DiaryData, 'title', $diaryStart, $perPage);
        ?>
        </div>
        <nav class="pagination">
            <?php
            // 日記ページネーションリンクの表示
            for ($i = 1; $i <= $totalDiaryPages; $i++) {
                if ($i == $diaryPage) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='?newsPage={$newsPage}&diaryPage={$i}'>{$i}</a> ";
                }
            }
            ?>
        </nav>
    </div>
</body>
</html>
